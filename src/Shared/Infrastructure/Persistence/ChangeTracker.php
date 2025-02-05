<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

final class ChangeTracker
{
    private array $snapshots = [];
    private array $trackedObjects = [];
    private array $newObjects = [];

    public function track(object $domainModel): void
    {
        $this->snapshots[spl_object_hash($domainModel)] = $this->snapshot($domainModel);
//        $this->trackedObjects[spl_object_hash($domainModel)] = \WeakReference::create($domainModel);
        $this->trackedObjects[spl_object_hash($domainModel)] = $domainModel;
    }

    public function trackAsNew(object $domainModel): void
    {
        $this->newObjects[spl_object_hash($domainModel)] = true;
        $this->track($domainModel);
    }

    public function hasChanged(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        if (!isset($this->snapshots[$hash])) {
            return false;
        }

        return $this->snapshot($domainModel) !== $this->snapshots[$hash];
    }

    public function isTracked(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        return isset($this->snapshots[$hash]);
    }

    public function isNew(object $domainModel): bool
    {
        $hash = spl_object_hash($domainModel);

        return isset($this->newObjects[$hash]);
    }

    // Not sure about needing this, since we might control everything in the mapper anyway,
    // and this would need a lot of work to make it a deep snapshot instead of a shallow one.
    public function getChanges(object $domainModel): array
    {
        $original = $this->snapshots[spl_object_hash($domainModel)] ?? [];
        $current = $this->snapshot($domainModel);
        return array_diff_assoc($current, $original);
    }

    private function snapshot(object $domainModel): array {
        return get_object_vars($domainModel);
    }

    public function getTrackedObjects(): array {
        $validObjects = [];

        foreach ($this->trackedObjects as $key => /*$weakRef*/$object) {
//            $object = $weakRef->get();
            if ($object !== null) {
                $validObjects[] = $object;
            } else {
                // Remove collected objects from tracking
                unset($this->trackedObjects[$key]);
            }
        }

        return $validObjects;
    }
}

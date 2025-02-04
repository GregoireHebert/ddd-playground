<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Models;

class Smartphone
{
    private function __construct(
        // pretends it's private and has getters
        public string $id,
        public string $label,
        public bool $enabled
    )
    {
    }

    public static function fromClassicApplication(
            string $id,
            string $label
    ) {
        // pretends it has invariants checks

        return new self(
            $id,
            $label,
            true
        );
    }

    public function toggle(): void
    {
        $this->enabled = !$this->enabled;
    }
}

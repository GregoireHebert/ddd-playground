<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Models;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\Identifier;

// pretend it's an aggregate
#[Aggregate]
class Smartphone
{
    private function __construct(
        // pretend it's private and has getters
        #[Identifier]
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
        // pretend it has invariants checks

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

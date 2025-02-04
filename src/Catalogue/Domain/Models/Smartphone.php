<?php

declare(strict_types=1);

namespace App\Catalogue\Domain\Models;

use App\Catalogue\Domain\Command\AddSmartphone;
use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
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

    #[CommandHandler("catalogue.aggregate.createSmartphone")]
    public static function directAggregateCall(
        AddSmartphone $command
    ): self {
        // pretend it has invariants checks

        return new self(
            $command->id,
            $command->label,
            true
        );
    }

    #[CommandHandler("catalogue.aggregate.toggleSmartphone")]
    public function toggle(): void
    {
        $this->enabled = !$this->enabled;
    }
}

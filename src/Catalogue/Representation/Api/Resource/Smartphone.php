<?php

declare(strict_types=1);

namespace App\Catalogue\Representation\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Catalogue\Representation\Api\State\Provider\SmartphoneRead;

#[ApiResource(
    operations: [
        new Get(provider: SmartphoneRead::class)
    ]
)]
class Smartphone
{
    public function __construct(
        public readonly string $id,
        public readonly string $label,
    ) {
    }
}

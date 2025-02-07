<?php

declare(strict_types=1);

namespace App\Catalogue\Representation\Api\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Catalogue\Representation\Api\State\Provider\SmartphoneRead;
use App\Catalogue\Representation\Api\State\Provider\SmartphonesRead;

#[ApiResource(
    operations: [
        new Get(provider: SmartphoneRead::class),
        new GetCollection(provider: SmartphonesRead::class)
    ]
)]
class Smartphone
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public readonly string $id,
        public readonly string $label,
    ) {
    }
}

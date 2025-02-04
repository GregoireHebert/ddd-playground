<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Products;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class SpecificPhone extends Phone
{
    #[Column]
    public string $verySpecificInfo;
}

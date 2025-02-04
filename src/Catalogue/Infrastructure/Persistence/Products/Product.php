<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Products;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;

#[Entity]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap([
    'phone' => Phone::class,
    'wearable' => Wearable::class,
    'specific' => SpecificPhone::class
])]
abstract class Product
{
    #[Id, GeneratedValue, Column]
    public int $id;
    #[Column]
    public string $label;
}

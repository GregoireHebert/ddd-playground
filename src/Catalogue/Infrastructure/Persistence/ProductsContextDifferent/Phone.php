<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\ProductsContextDifferent;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('product')]
class Phone
{
    #[Id, GeneratedValue, Column]
    public int $id;
    #[Column]
    public string $label;
    #[Column]
    public string $discr='phone';
}

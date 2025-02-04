<?php

declare(strict_types=1);

namespace App\Catalogue\Infrastructure\Persistence\Products;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Phone extends Product
{
    #[Column]
    public string $phoneSpecificInfo;

    private function __construct()
    {
    }

    public static function from(
        int $id,
        string $label,
        string $phoneSpecificInfo
    ) {
        $phone = new self();
        $phone->id = $id;
        $phone->label = $label.'fwef';
        $phone->phoneSpecificInfo = $phoneSpecificInfo.'fwef';

        return $phone;
    }
}

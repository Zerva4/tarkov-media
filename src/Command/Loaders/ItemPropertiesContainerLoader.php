<?php

declare(strict_types=1);

namespace App\Command\Loaders;

use App\Interfaces\Item\Properties\ItemPropertiesContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ItemPropertiesContainerLoader
{
    public function load(
        EntityManagerInterface $em,
        ItemPropertiesContainerInterface $entityProperties,
        array $arrayProperties,
        string $locale = '%app.default_locale%'
    ): ItemPropertiesContainerInterface
    {
        $entityProperties->setCapacity($arrayProperties['capacity'] ?? 0);
        // todo: ItemStorageGrid

        return $entityProperties;
    }
}
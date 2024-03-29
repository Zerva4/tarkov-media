<?php

declare(strict_types=1);

namespace App\Entity\Item\Properties;

use App\Interfaces\Item\Properties\ItemPropertiesInterface;
use App\Interfaces\Item\Properties\ItemPropertiesKeyInterface;
use App\Repository\Item\Properties\ItemPropertiesKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'items_properties_key', options: ['comment' => 'Таблица свойств для ключей'])]
#[ORM\Entity(repositoryClass: ItemPropertiesKeyRepository::class)]
class ItemPropertiesKey extends ItemProperties implements ItemPropertiesInterface, ItemPropertiesKeyInterface
{
    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Используется кол-во раз'])]
    private int $uses;

    public function getUses(): int
    {
        return $this->uses;
    }

    public function setUses(int $uses): ItemPropertiesKeyInterface
    {
        $this->uses = $uses;

        return $this;
    }
}

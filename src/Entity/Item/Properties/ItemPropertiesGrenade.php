<?php

declare(strict_types=1);

namespace App\Entity\Item\Properties;

use App\Interfaces\Item\Properties\ItemPropertiesGrenadeInterface;
use App\Interfaces\Item\Properties\ItemPropertiesInterface;
use App\Repository\Item\Properties\ItemPropertiesGrenadeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'items_properties_grenade', options: ['comment' => 'Таблица свойств для гранат'])]
#[ORM\Entity(repositoryClass: ItemPropertiesGrenadeRepository::class)]
class ItemPropertiesGrenade extends ItemProperties implements ItemPropertiesInterface, ItemPropertiesGrenadeInterface
{
    #[ORM\Column(type: 'string', length: 32, nullable: false, options: ['default' => '', 'comment' => 'Тип гранаты'])]
    private string $type;

    #[ORM\Column(type: 'float', nullable: false, options: ['default' => 0.0, 'comment' => 'Задержка перед взрывом'])]
    private float $fuse;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Мин. расстояние взрыва'])]
    private int $minExplosionDistance;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Макс. расстояние взрыва'])]
    private int $maxExplosionDistance;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Кол-во осколков'])]
    private int $fragments;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Радиус кантузии'])]
    private int $contusionRadius;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): ItemPropertiesGrenadeInterface
    {
        $this->type = $type;

        return $this;
    }

    public function getFuse(): float
    {
        return $this->fuse;
    }

    public function setFuse(float $fuse): ItemPropertiesGrenadeInterface
    {
        $this->fuse = $fuse;

        return $this;
    }

    public function getMinExplosionDistance(): int
    {
        return $this->minExplosionDistance;
    }

    public function setMinExplosionDistance(int $minExplosionDistance): ItemPropertiesGrenadeInterface
    {
        $this->minExplosionDistance = $minExplosionDistance;

        return $this;
    }

    public function getMaxExplosionDistance(): int
    {
        return $this->maxExplosionDistance;
    }

    public function setMaxExplosionDistance(int $maxExplosionDistance): ItemPropertiesGrenadeInterface
    {
        $this->maxExplosionDistance = $maxExplosionDistance;

        return $this;
    }

    public function getFragments(): int
    {
        return $this->fragments;
    }

    public function setFragments(int $fragments): ItemPropertiesGrenadeInterface
    {
        $this->fragments = $fragments;

        return $this;
    }

    public function getContusionRadius(): int
    {
        return $this->contusionRadius;
    }

    public function setContusionRadius(int $contusionRadius): ItemPropertiesGrenadeInterface
    {
        $this->contusionRadius = $contusionRadius;

        return $this;
    }
}

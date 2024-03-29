<?php

declare(strict_types=1);

namespace App\Interfaces\Item;

use App\Interfaces\Item\Properties\ItemPropertiesInterface;
use Doctrine\Common\Collections\Collection;

interface ItemStorageGridInterface
{
    public function getWidth(): ?int;
    public function setWidth(?int $width): ItemStorageGridInterface;
    public function getHeight(): ?int;
    public function setHeight(?int $height): ItemStorageGridInterface;
    public function getProperties(): Collection;
    public function setProperties(Collection $properties): ItemStorageGridInterface;
    public function addProperties(ItemPropertiesInterface $properties): ItemStorageGridInterface;
    public function removeProperties(ItemPropertiesInterface $properties): ItemStorageGridInterface;
}
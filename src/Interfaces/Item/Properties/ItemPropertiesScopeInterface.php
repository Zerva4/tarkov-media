<?php

declare(strict_types=1);

namespace App\Interfaces\Item\Properties;

interface ItemPropertiesScopeInterface
{
    public function getErgonomics(): float;
    public function setErgonomics(float $ergonomics): ItemPropertiesScopeInterface;
    public function getSightModes(): ?array;
    public function setSightModes(?array $sightModes): ItemPropertiesScopeInterface;
    public function getSightingRange(): int;
    public function setSightingRange(int $sightingRange): ItemPropertiesScopeInterface;
    public function getRecoilModifier(): float;
    public function setRecoilModifier(float $recoilModifier): ItemPropertiesScopeInterface;
    public function getZoomLevels(): ?array;
    public function setZoomLevels(?array $zoomLevels): ItemPropertiesScopeInterface;
}
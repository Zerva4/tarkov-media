<?php

declare(strict_types=1);

namespace App\Interfaces\Item\Properties;

interface ItemPropertiesNightVisionInterface
{
    public function getIntensity(): float;
    public function setIntensity(float $intensity): ItemPropertiesNightVisionInterface;
    public function getNoiseIntensity(): float;
    public function setNoiseIntensity(float $noiseIntensity): ItemPropertiesNightVisionInterface;
    public function getNoiseScale(): float;
    public function setNoiseScale(float $noiseScale): ItemPropertiesNightVisionInterface;
    public function getDiffuseIntensity(): float;
    public function setDiffuseIntensity(float $diffuseIntensity): ItemPropertiesNightVisionInterface;
}
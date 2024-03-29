<?php

declare(strict_types=1);

namespace App\Interfaces\Item;

use App\Interfaces\Item\Properties\ItemPropertiesInterface;

interface StimulationEffectInterface
{
    public function getType(): ?string;
    public function setType(?string $type): StimulationEffectInterface;
    public function getChance(): ?float;
    public function setChance(?float $chance): StimulationEffectInterface;
    public function getDelay(): ?int;
    public function setDelay(?int $delay): StimulationEffectInterface;
    public function getDuration(): ?int;
    public function setDuration(?int $duration): StimulationEffectInterface;
    public function getValue(): ?float;
    public function setValue(?float $value): StimulationEffectInterface;
    public function isPercent(): bool;
    public function getSkillName(): ?string;
    public function setSkillName(?string $skillName): StimulationEffectInterface;
    public function setPercent(bool $percent): StimulationEffectInterface;
    public function getProperties(): ?ItemPropertiesInterface;
    public function setProperties(?ItemPropertiesInterface $properties): StimulationEffectInterface;
}
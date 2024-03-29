<?php

declare(strict_types=1);

namespace App\Entity\Item\Properties;

use App\Interfaces\Item\Properties\ItemPropertiesHeadphoneInterface;
use App\Interfaces\Item\Properties\ItemPropertiesInterface;
use App\Repository\Item\Properties\ItemPropertiesHeadphoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'items_properties_headphone', options: ['comment' => 'Таблица свойств для наушников'])]
#[ORM\Entity(repositoryClass: ItemPropertiesHeadphoneRepository::class)]
class ItemPropertiesHeadphone  extends ItemProperties implements ItemPropertiesInterface, ItemPropertiesHeadphoneInterface
{
    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Звуки окружения'])]
    private int $ambientVolume;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Время срабатывания сжатия'])]
    private int $compressorAttack;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Общий уровень громкости'])]
    private int $compressorGain;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Скорость восстановления звука'])]
    private int $compressorRelease;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Уровень срабатывания сжатия'])]
    private int $compressorThreshold;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Глубина сжатия'])]
    private int $compressorVolume;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Обрезаемая частота'])]
    private int $cutoffFrequency;

    #[ORM\Column(type: 'float', nullable: false, options: ['default' => 0, 'comment' => 'Увеличение дальности звука'])]
    private float $distanceModifier;

    #[ORM\Column(type: 'float', nullable: false, options: ['default' => 0, 'comment' => 'Перекручивание звука уже готового'])]
    private float $distortion;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0, 'comment' => 'Уровень чистого звука'])]
    private int $dryVolume;

    #[ORM\Column(type: 'float', nullable: false, options: ['default' => 0, 'comment' => 'Высокочастные шумы'])]
    private float $highFrequencyGain;

    #[ORM\Column(type: 'float', nullable: false, options: ['default' => 0, 'comment' => 'Резонанс (Работает вместе с highFrequencyGain)'])]
    private float $resonance;

    public function getAmbientVolume(): int
    {
        return $this->ambientVolume;
    }

    public function setAmbientVolume(int $ambientVolume): ItemPropertiesHeadphoneInterface
    {
        $this->ambientVolume = $ambientVolume;
        
        return $this;
    }

    public function getCompressorAttack(): int
    {
        return $this->compressorAttack;
    }

    public function setCompressorAttack(int $compressorAttack): ItemPropertiesHeadphoneInterface
    {
        $this->compressorAttack = $compressorAttack;

        return $this;
    }

    public function getCompressorGain(): int
    {
        return $this->compressorGain;
    }

    public function setCompressorGain(int $compressorGain): ItemPropertiesHeadphoneInterface
    {
        $this->compressorGain = $compressorGain;

        return $this;
    }

    public function getCompressorRelease(): int
    {
        return $this->compressorRelease;
    }

    public function setCompressorRelease(int $compressorRelease): ItemPropertiesHeadphoneInterface
    {
        $this->compressorRelease = $compressorRelease;

        return $this;
    }

    public function getCompressorThreshold(): int
    {
        return $this->compressorThreshold;
    }

    public function setCompressorThreshold(int $compressorThreshold): ItemPropertiesHeadphoneInterface
    {
        $this->compressorThreshold = $compressorThreshold;

        return $this;
    }

    public function getCompressorVolume(): int
    {
        return $this->compressorVolume;
    }

    public function setCompressorVolume(int $compressorVolume): ItemPropertiesHeadphoneInterface
    {
        $this->compressorVolume = $compressorVolume;

        return $this;
    }

    public function getCutoffFrequency(): int
    {
        return $this->cutoffFrequency;
    }

    public function setCutoffFrequency(int $cutoffFrequency): ItemPropertiesHeadphoneInterface
    {
        $this->cutoffFrequency = $cutoffFrequency;

        return $this;
    }

    public function getDistanceModifier(): float
    {
        return $this->distanceModifier;
    }

    public function setDistanceModifier(float $distanceModifier): ItemPropertiesHeadphoneInterface
    {
        $this->distanceModifier = $distanceModifier;

        return $this;
    }

    public function getDistortion(): float
    {
        return $this->distortion;
    }

    public function setDistortion(float $distortion): ItemPropertiesHeadphoneInterface
    {
        $this->distortion = $distortion;

        return $this;
    }

    public function getDryVolume(): int
    {
        return $this->dryVolume;
    }

    public function setDryVolume(int $dryVolume): ItemPropertiesHeadphoneInterface
    {
        $this->dryVolume = $dryVolume;

        return $this;
    }

    public function getHighFrequencyGain(): float
    {
        return $this->highFrequencyGain;
    }

    public function setHighFrequencyGain(float $highFrequencyGain): ItemPropertiesHeadphoneInterface
    {
        $this->highFrequencyGain = $highFrequencyGain;

        return $this;
    }

    public function getResonance(): float
    {
        return $this->resonance;
    }

    public function setResonance(float $resonance): ItemPropertiesHeadphoneInterface
    {
        $this->resonance = $resonance;

        return $this;
    }
}

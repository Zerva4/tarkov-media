<?php

declare(strict_types=1);

namespace App\Entity\Item;

use App\Entity\Item\Properties\ItemProperties;
use App\Entity\Item\Properties\ItemPropertiesFoodDrink;
use App\Entity\TranslatableEntity;
use App\Interfaces\Item\Properties\ItemPropertiesFoodDrinkInterface;
use App\Interfaces\Item\Properties\ItemPropertiesInterface;
use App\Interfaces\Item\StimulationEffectInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Repository\Item\StimulationEffectRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;

#[ORM\Entity(repositoryClass: StimulationEffectRepository::class)]
class StimulationEffect extends TranslatableEntity implements StimulationEffectInterface, UuidPrimaryKeyInterface, TimestampableInterface
{
    use UuidPrimaryKeyTrait;

    #[ORM\Column(type: 'float', nullable: true, options: ['default' => 0, 'comment' => ''])]
    private ?float $chance;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0, 'comment' => ''])]
    private ?int $delay;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 0, 'comment' => ''])]
    private ?int $duration;

    #[ORM\Column(type: 'float', nullable: true, options: ['default' => 0, 'comment' => ''])]
    private ?float $value;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => false, 'comment' => ''])]
    private bool $percent;

    #[ORM\ManyToOne(targetEntity: ItemProperties::class, inversedBy: 'stimulationEffects')]
    #[ORM\JoinColumn(name: 'properties_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?ItemPropertiesInterface $properties = null;

    public function __construct(string $defaultLocale = '%app.default_locale%')
    {
        parent::__construct($defaultLocale);
    }

    public function getType(): ?string
    {
        return $this->translate()->getType();
    }

    public function setType(?string $type): StimulationEffectInterface
    {
        $this->translate()->setType($type);

        return $this;
    }

    public function getChance(): ?float
    {
        return $this->chance;
    }

    public function setChance(?float $chance): StimulationEffectInterface
    {
        $this->chance = $chance;

        return $this;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): StimulationEffectInterface
    {
        $this->delay = $delay;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): StimulationEffectInterface
    {
        $this->duration = $duration;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): StimulationEffectInterface
    {
        $this->value = $value;

        return $this;
    }

    public function isPercent(): bool
    {
        return $this->percent;
    }

    public function setPercent(bool $percent): StimulationEffectInterface
    {
        $this->percent = $percent;

        return $this;
    }

    public function getSkillName(): ?string
    {
        return $this->translate()->getSkillName();
    }

    public function setSkillName(?string $skillName): StimulationEffectInterface
    {
        $this->translate()->setSkillName($skillName);

        return $this;
    }

    public function getProperties(): ?ItemPropertiesInterface
    {
        return $this->properties;
    }

    public function setProperties(?ItemPropertiesInterface $properties): StimulationEffectInterface
    {
        $this->properties = $properties;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}

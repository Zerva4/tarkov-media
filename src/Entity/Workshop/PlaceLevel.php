<?php

declare(strict_types=1);

namespace App\Entity\Workshop;

use App\Entity\Item\ContainedItem;
use App\Entity\Skill;
use App\Entity\Trader\TraderRequired;
use App\Entity\TranslatableEntity;
use App\Interfaces\Item\ContainedItemInterface;
use App\Interfaces\SkillInterface;
use App\Interfaces\Trader\TraderRequiredInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Interfaces\Workshop\PlaceInterface;
use App\Interfaces\Workshop\PlaceLevelInterface;
use App\Interfaces\Workshop\PlaceLevelRequiredInterface;
use App\Repository\Workshop\PlaceLevelRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Table(name: 'places_levels')]
#[ORM\Index(columns: ['api_id'], name: 'places_levels_api_id_idx')]
#[ORM\Entity(repositoryClass: PlaceLevelRepository::class)]
class PlaceLevel extends TranslatableEntity implements UuidPrimaryKeyInterface, TranslatableInterface, TimestampableInterface, PlaceLevelInterface
{
    use UuidPrimaryKeyTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $apiId = null;

    #[ORM\Column(type: 'boolean', nullable: false, options: ['default' => true])]
    private bool $published = true;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private int $levelOrder = 0;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private int $level = 0;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private int $constructionTime = 0;

    #[ORM\ManyToOne(targetEntity: Place::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', inversedBy: 'levels')]
    private ?PlaceInterface $place;

    #[ORM\ManyToMany(targetEntity: ContainedItem::class, inversedBy: 'requiredForPlacesLevels', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'places_levels_required_items')]
    private Collection $requiredItems;

    #[ORM\ManyToMany(targetEntity: PlaceLevelRequired::class, inversedBy: 'requiredForPlacesLevels', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: false)]
    #[ORM\JoinTable(name: 'places_levels_required_levels')]
    private Collection $requiredPlacesLevels;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'requiredForPlacesLevels', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'places_levels_required_skills')]
    private Collection $requiredSkills;

    #[ORM\ManyToMany(targetEntity: TraderRequired::class, inversedBy: 'requiredForPlacesLevels', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'places_levels_required_traders')]
    private Collection $requiredTraders;

    public function __construct(string $defaultLocation = '%app.default_locale%')
    {
        parent::__construct($defaultLocation);

        $this->requiredItems = new ArrayCollection();
        $this->requiredPlacesLevels = new ArrayCollection();
        $this->requiredSkills = new ArrayCollection();
        $this->requiredTraders = new ArrayCollection();
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(?string $apiId): PlaceLevelInterface
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): PlaceLevelInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getLevelOrder(): int
    {
        return $this->levelOrder;
    }

    public function setLevelOrder(int $levelOrder = 0): PlaceLevelInterface
    {
        $this->levelOrder = $levelOrder;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level = 0): PlaceLevelInterface
    {
        $this->level = $level;

        return $this;
    }

    public function getConstructionTime(): int
    {
        return $this->constructionTime;
    }

    public function setConstructionTime(int $constructionTime): PlaceLevelInterface
    {
        $this->constructionTime = $constructionTime;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->translate()->getDescription();
    }

    public function setDescription(string $description): PlaceLevelInterface
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    /**
     * @return PlaceInterface|null
     */
    public function getPlace(): ?PlaceInterface
    {
        return $this->place;
    }

    /**
     * @param PlaceInterface|null $place
     * @return PlaceLevelInterface
     */
    public function setPlace(?PlaceInterface $place): PlaceLevelInterface
    {
        $this->place = $place;

        return $this;
    }

    public function addPlace(PlaceInterface $place): PlaceLevelInterface
    {
        $place->addLevel($this);
        $this->place = $place;

        return $this;
    }

    public function removePlace(PlaceInterface $place): PlaceLevelInterface
    {
        $place->removeLevel($this);
        $this->place = $place;

        return $this;
    }

    public function getRequiredItems(): Collection
    {
        return $this->requiredItems;
    }

    public function setRequiredItems(Collection $requiredItems): PlaceLevelInterface
    {
        $this->requiredItems = $requiredItems;

        return $this;
    }

    public function addRequiredItem(ContainedItemInterface $item): PlaceLevelInterface
    {
        if (!$this->requiredItems->contains($item)) {
            $this->requiredItems->add($item);
            $item->addRequiredForPlacesLevel($this);
        }

        return $this;
    }

    public function removeRequiredItem(ContainedItemInterface $item): PlaceLevelInterface
    {
        if ($this->requiredItems->contains($item)) {
            $this->requiredItems->removeElement($item);
            $item->removeRequiredForPlacesLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRequiredPlacesLevels(): Collection
    {
        return $this->requiredPlacesLevels;
    }

    /**
     * @param Collection $requiredPlacesLevels
     * @return PlaceLevelInterface
     */
    public function setRequiredPlacesLevels(Collection $requiredPlacesLevels): PlaceLevelInterface
    {
        $this->requiredPlacesLevels = $requiredPlacesLevels;

        return $this;
    }

    public function addRequiredPlaceLevel(PlaceLevelRequiredInterface $requiredPlaceLevel): PlaceLevelInterface
    {
        if (!$this->requiredPlacesLevels->contains($requiredPlaceLevel)) {
            $this->requiredPlacesLevels->add($requiredPlaceLevel);
            $requiredPlaceLevel->addRequiredForPlacesLevel($this);
        }

        return $this;
    }

    public function removeRequiredPlaceLevel(PlaceLevelRequiredInterface $requiredPlaceLevel): PlaceLevelInterface
    {
        if ($this->requiredPlacesLevels->contains($requiredPlaceLevel)) {
            $this->requiredPlacesLevels->removeElement($requiredPlaceLevel);
            $requiredPlaceLevel->removeRequiredForPlacesLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRequiredSkills(): Collection
    {
        return $this->requiredSkills;
    }

    public function setRequiredSkills(Collection $requiredSkills): PlaceLevelInterface
    {
        $this->requiredSkills = $requiredSkills;

        return $this;
    }

    public function addRequiredSkill(SkillInterface $skill): PlaceLevelInterface
    {
        if (!$this->requiredSkills->contains($skill)) {
            $this->requiredSkills->add($skill);
            $skill->addRequiredForPlacesLevel($this);
        }

        return $this;
    }

    public function removeRequiredSkill(SkillInterface $skill): PlaceLevelInterface
    {
        if ($this->requiredSkills->contains($skill)) {
            $this->requiredSkills->removeElement($skill);
            $skill->removeRequiredForPlacesLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRequiredTraders(): Collection
    {
        return $this->requiredTraders;
    }

    /**
     * @param Collection $requiredTraders
     * @return PlaceLevelInterface
     */
    public function setRequiredTraders(Collection $requiredTraders): PlaceLevelInterface
    {
        $this->requiredTraders = $requiredTraders;

        return $this;
    }

    public function addRequiredTrader(TraderRequiredInterface $trader): PlaceLevelInterface
    {
        if (!$this->requiredTraders->contains($trader)) {
            $this->requiredTraders->add($trader);
            $trader->addRequiredForPlacesLevel($this);
        }

        return $this;
    }

    public function removeRequiredTrader(TraderRequiredInterface $trader): PlaceLevelInterface
    {
        if ($this->requiredTraders->contains($trader)) {
            $this->requiredTraders->removeElement($trader);
            $trader->removeRequiredForPlacesLevel($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getLevel();
    }
}

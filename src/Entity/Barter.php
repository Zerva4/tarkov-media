<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Item\ContainedItem;
use App\Entity\Quest\Quest;
use App\Entity\Trader\Trader;
use App\Interfaces\BarterInterface;
use App\Interfaces\Item\ContainedItemInterface;
use App\Interfaces\Quest\QuestInterface;
use App\Interfaces\Trader\TraderInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Repository\BarterRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Table(name: 'barters')]
#[ORM\Index(columns: ['api_id'], name: 'barter_api_key_idx')]
#[ORM\Entity(repositoryClass: BarterRepository::class)]
class Barter implements UuidPrimaryKeyInterface, TimestampableInterface, BarterInterface
{
    use UuidPrimaryKeyTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $apiId = null;

    #[ORM\Column(type: 'boolean')]
    private bool $published;

    #[ORM\ManyToOne(targetEntity: Trader::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', inversedBy: 'barters')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\JoinTable(name: 'barters_traders')]
    private TraderInterface $trader;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $traderLevel;

    #[ORM\ManyToOne(targetEntity: Quest::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', inversedBy: 'unlockBarters')]
    #[ORM\JoinColumn(name: 'quest_unlock_barters', referencedColumnName: 'id')]
    private ?QuestInterface $unlockInQuest = null;

    #[ORM\ManyToMany(targetEntity: ContainedItem::class, inversedBy: 'requiredInBarters', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'barters_required_items')]
    private Collection $requiredItems;

    #[ORM\ManyToMany(targetEntity: ContainedItem::class, inversedBy: 'rewardInBarters', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'barters_reward_items')]
    private Collection $rewardItems;

    public function __construct()
    {
        $this->requiredItems = new ArrayCollection();
        $this->rewardItems = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getApiId(): string
    {
        return $this->apiId;
    }

    /**
     * @param string $apiId
     * @return BarterInterface
     */
    public function setApiId(string $apiId): BarterInterface
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     * @return BarterInterface
     */
    public function setPublished(bool $published): BarterInterface
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return TraderInterface|null
     */
    public function getTrader(): ?TraderInterface
    {
        return $this->trader;
    }

    /**
     * @param TraderInterface $trader
     * @return BarterInterface
     */
    public function setTrader(TraderInterface $trader): BarterInterface
    {
        $this->trader = $trader;

        return $this;
    }

    /**
     * @return int
     */
    public function getTraderLevel(): int
    {
        return $this->traderLevel;
    }

    /**
     * @param int $traderLevel
     * @return BarterInterface
     */
    public function setTraderLevel(int $traderLevel): BarterInterface
    {
        $this->traderLevel = $traderLevel;

        return $this;
    }

    /**
     * @return QuestInterface|null
     */
    public function getUnlockInQuest(): ?QuestInterface
    {
        return $this->unlockInQuest;
    }

    /**
     * @param QuestInterface|null $quest
     * @return BarterInterface
     */
    public function setUnlockInQuest(?QuestInterface $quest): BarterInterface
    {
        if (null === $quest) {
            $this->unlockInQuest->removeUnlockBarter($this);
            $this->unlockInQuest = null;
        } else {
            $this->unlockInQuest = $quest;
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRequiredItems(): Collection
    {
        return $this->requiredItems;
    }

    /**
     * @param Collection $requiredItems
     * @return BarterInterface
     */
    public function setRequiredItems(Collection $requiredItems): BarterInterface
    {
        $this->requiredItems = $requiredItems;

        return $this;
    }

    public function addRequiredItem(ContainedItemInterface $item): BarterInterface
    {
        if (!$this->requiredItems->contains($item)) {
            $this->requiredItems->add($item);
            $item->addRequiredInBarter($this);
        }

        return $this;
    }

    public function removeRequiredItem(ContainedItemInterface $item): BarterInterface
    {
        if ($this->requiredItems->contains($item)) {
            $this->requiredItems->removeElement($item);
            $item->removeRequiredInBarter($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRewardItems(): Collection
    {
        return $this->rewardItems;
    }

    /**
     * @param Collection $rewardItems
     * @return BarterInterface
     */
    public function setRewardItems(Collection $rewardItems): BarterInterface
    {
        $this->rewardItems = $rewardItems;

        return $this;
    }

    /**
     * @param ContainedItemInterface $item
     * @return BarterInterface
     */
    public function addRewardItem(ContainedItemInterface $item): BarterInterface
    {
        if (!$this->rewardItems->contains($item)) {
            $this->rewardItems->add($item);
            $item->addRewardInBarter($this);
        }

        return $this;
    }

    /**
     * @param ContainedItemInterface $item
     * @return BarterInterface
     */
    public function removeRewardItem(ContainedItemInterface $item): BarterInterface
    {
        if (!$this->rewardItems->contains($item)) {
            $this->rewardItems->removeElement($item);
            $item->removeRewardInBarter($this);
        }

        return $this;
    }
}

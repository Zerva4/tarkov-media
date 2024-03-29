<?php

declare(strict_types=1);

namespace App\Entity\Quest;

use App\Entity\Barter;
use App\Entity\Item\ContainedItem;
use App\Entity\Map;
use App\Entity\Trader\Trader;
use App\Entity\Trader\TraderCashOffer;
use App\Entity\Trader\TraderStanding;
use App\Entity\TranslatableEntity;
use App\Entity\Workshop\Craft;
use App\Interfaces\BarterInterface;
use App\Interfaces\Item\ContainedItemInterface;
use App\Interfaces\MapInterface;
use App\Interfaces\Quest\QuestAdviceInterface;
use App\Interfaces\Quest\QuestInterface;
use App\Interfaces\Quest\QuestKeyInterface;
use App\Interfaces\Quest\QuestObjectiveInterface;
use App\Interfaces\Trader\TraderCashOfferInterface;
use App\Interfaces\Trader\TraderInterface;
use App\Interfaces\Trader\TraderStandingInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Interfaces\Workshop\CraftInterface;
use App\Repository\Quest\QuestRepository;
use App\Traits\SlugTrait;
use App\Traits\UuidPrimaryKeyTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Table(name: 'quests')]
#[ORM\Index(columns: ['slug'], name: 'quests_slug_idx')]
#[ORM\Index(columns: ['api_id'], name: 'quests_api_key_idx')]
#[ORM\Entity(repositoryClass: QuestRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
/**
 * @Vich\Uploadable
 */
class Quest extends TranslatableEntity implements UuidPrimaryKeyInterface, TranslatableInterface, TimestampableInterface, QuestInterface
{
    use UuidPrimaryKeyTrait;
    use TimestampableTrait;
    use SlugTrait;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $apiId;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 0])]
    private int $position = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $published;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'quests', fileNameProperty: 'imageName')]
    #[Assert\Valid]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/gif', 'image/jpeg', 'image/png']
    )]
    /**
     * @Vich\UploadableField(mapping="quests", fileNameProperty="imageName")
     * @Assert\Valid
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={
     *         "image/jpg", "image/gif", "image/jpeg", "image/png"
     *     }
     * )
     */
    private ?File $imageFile = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $experience = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $minPlayerLevel = 1;

    #[ORM\Column(type: 'boolean', nullable: false, options: ["default" => false])]
    private bool $restartable = false;

    #[ORM\Column(type: 'boolean', nullable: false, options: ["default" => false])]
    private bool $kappaRequired = false;

    #[ORM\Column(type: 'boolean', nullable: false, options: ["default" => false])]
    private bool $lightkeeperRequired = false;

    #[ORM\ManyToOne(targetEntity: Trader::class, cascade: ['persist'], inversedBy: 'quests')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?TraderInterface $trader = null;

    #[ORM\ManyToOne(targetEntity: Map::class, inversedBy: 'quests')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?MapInterface $map = null;

    #[ORM\OneToMany(mappedBy: 'unlockInQuest', targetEntity: Barter::class, cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: false)]
    #[ORM\JoinColumn(name: 'quest_unlock', referencedColumnName: 'id', onDelete: 'SET NULL')]
    #[ORM\JoinTable(name: 'quests_unlock_barters')]
    private Collection $unlockBarters;

    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: QuestObjective::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $objectives;

    #[ORM\ManyToMany(targetEntity: ContainedItem::class, inversedBy: 'usedInQuests', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'quests_used_items')]
    private Collection $usedItems;

    #[ORM\ManyToMany(targetEntity: ContainedItem::class, inversedBy: 'receivedFromQuests', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'quests_received_items')]
    private Collection $receivedItems;

    #[ORM\OneToMany(mappedBy: 'unlockQuest', targetEntity: Craft::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $unlockInCrafts;

    #[ORM\OneToMany(mappedBy: 'questUnlock', targetEntity: TraderCashOffer::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $unlockInCashOffers;

    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: QuestKey::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private ?Collection $neededKeys;

    #[ORM\OneToMany(mappedBy: 'quest', targetEntity: TraderStanding::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $traderStandings;

    #[ORM\ManyToMany(targetEntity: QuestAdvice::class, mappedBy: 'quests', cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable('quests_advice_mapping')]
    private Collection $advices;

    public function __construct(string $defaultLocale = '%app.default_locale%')
    {
        parent::__construct($defaultLocale);

        $this->objectives = new ArrayCollection();
        $this->usedItems = new ArrayCollection();
        $this->receivedItems = new ArrayCollection();
        $this->unlockBarters = new ArrayCollection();
        $this->unlockInCrafts = new ArrayCollection();
        $this->unlockInCashOffers = new ArrayCollection();
        $this->neededKeys = new ArrayCollection();
        $this->traderStandings = new ArrayCollection();
        $this->advices = new ArrayCollection();
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(string $apiId): QuestInterface
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): QuestInterface
    {
        $this->position = $position;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): QuestInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->translate()->getName();
    }

    public function setName(string $name): QuestInterface
    {
        $this->translate()->setName($name);

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->translate()->getShortName();
    }

    public function setShortName(string $name): QuestInterface
    {
        $this->translate()->setShortName($name);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->translate()->getDescription();
    }

    public function setDescription(string $description): QuestInterface
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    public function getHowToComplete(): ?string
    {
        return $this->translate()->getHowToComplete();
    }

    public function setHowToComplete(string $howToComplete): QuestInterface
    {
        $this->translate()->setHowToComplete($howToComplete);

        return $this;
    }

    public function getStartDialog(): ?string
    {
        return $this->translate()->getStartDialog();
    }

    public function setStartDialog(?string $startDialog): QuestInterface
    {
        $this->translate()->setStartDialog($startDialog);

        return $this;
    }

    public function getSuccessfulDialog(): ?string
    {
        return $this->translate()->getSuccessfulDialog();
    }

    public function setSuccessfulDialog(?string $successfulDialog): QuestInterface
    {
        $this->translate()->setSuccessfulDialog($successfulDialog);

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): QuestInterface
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getTrader(): ?TraderInterface
    {
        return $this->trader;
    }

    public function setTrader(?TraderInterface $trader): QuestInterface
    {
        $this->trader = $trader;

        return $this;
    }

    public function getMap(): ?MapInterface
    {
        return $this->map;
    }

    public function setMap(?MapInterface $map): QuestInterface
    {
        $this->map = $map;
        $map->addQuest($this);

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): QuestInterface
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new DateTime('NOW');
        }

        return $this;
    }

    public function isRestartable(): bool
    {
        return $this->restartable;
    }

    public function setRestartable(bool $restartable): QuestInterface
    {
        $this->restartable = $restartable;

        return $this;
    }

    public function isKappaRequired(): bool
    {
        return $this->kappaRequired;
    }

    public function setKappaRequired(bool $kappaRequired): QuestInterface
    {
        $this->kappaRequired = $kappaRequired;

        return $this;
    }

    public function isLightkeeperRequired(): bool
    {
        return $this->lightkeeperRequired;
    }

    public function setLightkeeperRequired(bool $lightkeeperRequired): QuestInterface
    {
        $this->lightkeeperRequired = $lightkeeperRequired;

        return $this;
    }

    public function getObjectives(): Collection
    {
        return $this->objectives;
    }

    public function setObjectives(Collection $objectives): QuestInterface
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function addObjective(QuestObjectiveInterface ...$objectives): QuestInterface
    {
        foreach ($objectives as $objective) {
            if (!$this->objectives->contains($objective)) {
                $this->objectives->add($objective);
                $objective->setQuest($this);
            }
        }

        return $this;
    }

    public function removeObjective(QuestObjectiveInterface $objective): QuestInterface
    {
        if ($this->objectives->contains($objective)) {
            $this->objectives->removeElement($objective);
            $objective->setQuest(null);
        }

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): QuestInterface
    {
        $this->experience = $experience;

        return $this;
    }

    public function getMinPlayerLevel(): int
    {
        return $this->minPlayerLevel;
    }

    public function setMinPlayerLevel(int $minPlayerLevel): QuestInterface
    {
        $this->minPlayerLevel = $minPlayerLevel;

        return $this;
    }

    public function getUsedItems(): Collection
    {
        return $this->usedItems;
    }

    public function setUsedItems(Collection $usedItems): QuestInterface
    {
        $this->usedItems = $usedItems;

        return $this;
    }

    public function addUsedItem(ContainedItemInterface $item): QuestInterface
    {
        if (!$this->usedItems->contains($item)) {
            $this->usedItems->add($item);
            $item->addUsedInQuest($this);
        }

        return $this;
    }

    public function removeUsedItem(ContainedItemInterface $item): QuestInterface
    {
        if ($this->usedItems->contains($item)) {
            $this->usedItems->removeElement($item);
            $item->removeUsedInQuest($this);
        }

        return $this;
    }

    public function getReceivedItems(): Collection
    {
        return $this->receivedItems;
    }

    public function setReceivedItems(?Collection $receivedItems): QuestInterface
    {
        $this->receivedItems = $receivedItems;

        return $this;
    }

    public function addReceivedItem(ContainedItemInterface $item): QuestInterface
    {
        if (!$this->receivedItems->contains($item)) {
            $this->receivedItems->add($item);
            $item->addReceivedFromQuest($this);
        }

        return $this;
    }

    public function removeReceivedItem(ContainedItemInterface $item): QuestInterface
    {
        if ($this->receivedItems->contains($item)) {
            $this->receivedItems->removeElement($item);
            $item->removeReceivedFromQuest($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUnlockBarters(): Collection
    {
        return $this->unlockBarters;
    }

    /**
     * @param Collection $unlockBarters
     * @return QuestInterface
     */
    public function setUnlockBarters(Collection $unlockBarters): QuestInterface
    {
        $this->unlockBarters = $unlockBarters;

        return $this;
    }

    /**
     * @param BarterInterface $barter
     * @return QuestInterface
     */
    public function addUnlockBarter(BarterInterface $barter): QuestInterface
    {
        if (!$this->unlockBarters->contains($barter)) {
            $this->unlockBarters->add($barter);
            $barter->setUnlockInQuest($this);
        }

        return $this;
    }

    /**
     * @param BarterInterface $barter
     * @return QuestInterface
     */
    public function removeUnlockBarter(BarterInterface $barter): QuestInterface
    {
        if ($this->unlockBarters->contains($barter)) {
            $this->unlockBarters->add($barter);
            $barter->setUnlockInQuest(null);
        }

        return $this;
    }

    public function getUnlockInCrafts(): Collection
    {
        return $this->unlockInCrafts;
    }

    public function setUnlockInCrafts(Collection $unlockInCrafts): QuestInterface
    {
        $this->unlockInCrafts = $unlockInCrafts;

        return $this;
    }

    public function addUnlockInCraft(CraftInterface $craft): QuestInterface
    {
        if (!$this->unlockInCrafts->contains($craft)) {
            $this->unlockInCrafts->add($craft);
            $craft->setUnlockQuest($this);
        }

        return $this;
    }

    public function removeUnlockInCraft(CraftInterface $craft): QuestInterface
    {
        if ($this->unlockInCrafts->contains($craft)) {
            $this->unlockInCrafts->add($craft);
            $craft->setUnlockQuest(null);
        }

        return $this;
    }

    public function getUnlockInCashOffers(): Collection
    {
        return $this->unlockInCashOffers;
    }

    public function setUnlockInCashOffers(Collection $unlockInCashOffers): QuestInterface
    {
        $this->unlockInCashOffers = $unlockInCashOffers;

        return $this;
    }

    public function addUnlockInCashOffer(TraderCashOfferInterface $cashOffer): QuestInterface
    {
        if (!$this->unlockInCashOffers->contains($cashOffer)) {
            $this->unlockInCashOffers->add($cashOffer);
            $cashOffer->setQuestUnlock($this);
        }

        return $this;
    }

    public function removeUnlockInCashOffer(TraderCashOfferInterface $cashOffer): QuestInterface
    {
        if ($this->unlockInCashOffers->contains($cashOffer)) {
            $this->unlockInCashOffers->add($cashOffer);
            $cashOffer->setQuestUnlock(null);
        }

        return $this;
    }

    public function getNeededKeys(): ?Collection
    {
        return $this->neededKeys;
    }

    public function setNeededKeys(?Collection $neededKeys): QuestInterface
    {
        $this->neededKeys = $neededKeys;

        return $this;
    }

    public function addNeededKey(QuestKeyInterface $questKey): QuestInterface
    {
        if (!$this->neededKeys->contains($questKey)) {
            $this->neededKeys->add($questKey);
            $questKey->setQuest($this);
        }

        return $this;
    }

    public function removeNeededKey(QuestKeyInterface $questKey): QuestInterface
    {
        if ($this->neededKeys->contains($questKey)) {
            $this->neededKeys->removeElement($questKey);
        }

        return $this;
    }

    public function getTraderStandings(): Collection
    {
        return $this->traderStandings;
    }

    public function setTraderStandings(Collection $traderStandings): QuestInterface
    {
        $this->traderStandings = $traderStandings;

        return $this;
    }

    public function addTraderStanding(TraderStandingInterface $traderStanding): QuestInterface
    {
        if (!$this->traderStandings->contains($traderStanding)) {
            $this->traderStandings->add($traderStanding);
            $traderStanding->setQuest($this);
        }

        return $this;
    }

    public function removeTraderStanding(TraderStandingInterface $traderStanding): QuestInterface
    {
        if ($this->traderStandings->contains($traderStanding)) {
            $this->traderStandings->removeElement($traderStanding);
            $traderStanding->setQuest(null);
        }

        return $this;
    }

    public function getAdvices(): Collection
    {
        return $this->advices;
    }

    public function getRandomAdvice()
    {
        $advices = $this->getAdvices()->toArray();
    }

    public function setAdvices(Collection $advices): QuestInterface
    {
        $this->advices = $advices;

        return $this;
    }

    public function addAdvice(QuestAdviceInterface $advice): QuestInterface
    {
        if (!$this->advices->contains($advice)) {
            $this->advices->add($advice);
            $advice->addQuest($this);
        }

        return $this;
    }

    public function removeAdvice(QuestAdviceInterface $advice): QuestInterface
    {
        if ($this->advices->contains($advice)) {
            $this->advices->removeElement($advice);
            $advice->removeQuest($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}

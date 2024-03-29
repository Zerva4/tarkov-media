<?php

declare(strict_types=1);

namespace App\Entity\Quest;

use App\Entity\TranslatableEntity;
use App\Entity\Workshop\Craft;
use App\Interfaces\Quest\QuestItemInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Interfaces\Workshop\CraftInterface;
use App\Repository\Quest\QuestItemRepository;
use App\Traits\UuidPrimaryKeyTrait;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Table(name: 'quests_items')]
#[ORM\Index(columns: ['slug'], name: 'quests_items_slug_idx')]
#[ORM\Index(columns: ['api_id'], name: 'quests_items_api_key_idx')]
#[ORM\Entity(repositoryClass: QuestItemRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
/**
 * @Vich\Uploadable
 */
class QuestItem extends TranslatableEntity implements UuidPrimaryKeyInterface, QuestItemInterface, TranslatableInterface
{
    use UuidPrimaryKeyTrait;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $apiId;

    #[ORM\Column(type: 'boolean')]
    private bool $published;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $width = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $height = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(type: 'string', length: 255, unique: false, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Invalid format', match: true)]
    private string $slug;

    #[Vich\UploadableField(mapping: 'quests_items', fileNameProperty: 'imageName')]
    #[Assert\Valid]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/gif', 'image/jpeg', 'image/png']
    )]
    /**
     * @Vich\UploadableField(mapping="quests_items", fileNameProperty="imageName")
     * @Assert\Valid
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={
     *         "image/jpg", "image/gif", "image/jpeg", "image/png"
     *     }
     * )
     */
    private ?File $imageFile = null;

    #[ORM\ManyToMany(targetEntity: Craft::class, mappedBy: 'requiredQuestItems', cascade: ['persist'], fetch: 'EXTRA_LAZY', orphanRemoval: false)]
    #[ORM\JoinTable(name: 'crafts_required_quest_items')]
    private Collection $requiredInCrafts;

    public function __construct(string $defaultLocation = '%app.default_locale%')
    {
        parent::__construct($defaultLocation);
    }

    public function getApiId(): string
    {
        return $this->apiId;
    }

    public function setApiId(string $apiId): QuestItemInterface
    {
        $this->apiId = $apiId;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): QuestItemInterface
    {
        $this->published = $published;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->translate()->getName();
    }

    public function setName(string $name): QuestItemInterface
    {
        $this->translate()->setName($name);

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->translate()->getShortName();
    }

    public function setShortName(string $name): QuestItemInterface
    {
        $this->translate()->setShortName($name);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->translate()->getDescription();
    }

    public function setDescription(string $description): QuestItemInterface
    {
        $this->translate()->setDescription($description);

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): QuestItemInterface
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): QuestItemInterface
    {
        $this->height = $height;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): QuestItemInterface
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): QuestItemInterface
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new DateTime('NOW');
        }

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRequiredInCrafts(): Collection
    {
        return $this->requiredInCrafts;
    }

    public function setRequiredInCrafts(Collection $requiredInCrafts): QuestItemInterface
    {
        $this->requiredInCrafts = $requiredInCrafts;

        return $this;
    }

    public function addRequiredInCraft(CraftInterface $craft): QuestItemInterface
    {
        if (!$this->requiredInCrafts->contains($craft)) {
            $this->requiredInCrafts->add($craft);
            $craft->addRequiredQuestItem($this);
        }

        return $this;
    }

    public function removeRequiredInCraft(CraftInterface $craft): QuestItemInterface
    {
        if ($this->requiredInCrafts->contains($craft)) {
            $this->requiredInCrafts->removeElement($craft);
            $craft->removeRequiredQuestItem($this);
        }

        return $this;
    }
}

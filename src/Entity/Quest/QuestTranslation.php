<?php

declare(strict_types=1);

namespace App\Entity\Quest;

use App\Interfaces\UuidPrimaryKeyInterface;
use App\Repository\Quest\QuestTranslationRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

#[ORM\Table(name: 'quests_translation')]
#[ORM\Index(columns: ['locale'], name: 'quests_locale_idx')]
#[ORM\Entity(repositoryClass: QuestTranslationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class QuestTranslation implements UuidPrimaryKeyInterface, TranslationInterface, TimestampableInterface
{
    use UuidPrimaryKeyTrait;
    use TranslationTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $howToComplete = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $startDialog = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $successfulDialog = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHowToComplete(): ?string
    {
        return $this->howToComplete;
    }

    public function setHowToComplete(string $howToComplete): self
    {
        $this->howToComplete = $howToComplete;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStartDialog(): ?string
    {
        return $this->startDialog;
    }

    /**
     * @param string|null $startDialog
     * @return QuestTranslation
     */
    public function setStartDialog(?string $startDialog): self
    {
        $this->startDialog = $startDialog;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSuccessfulDialog(): ?string
    {
        return $this->successfulDialog;
    }

    /**
     * @param string|null $successfulDialog
     * @return QuestTranslation
     */
    public function setSuccessfulDialog(?string $successfulDialog): self
    {
        $this->successfulDialog = $successfulDialog;

        return $this;
    }
}

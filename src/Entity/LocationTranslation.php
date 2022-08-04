<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationLangsRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Table(name: 'locations_translation')]
#[ORM\Entity(repositoryClass: LocationLangsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class LocationTranslation implements TranslationInterface
{
    use UuidPrimaryKeyTrait;
    use TranslationTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: 'translatable_id', referencedColumnName: 'id')]
    protected $translatable;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}

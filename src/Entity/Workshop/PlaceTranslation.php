<?php

declare(strict_types=1);

namespace App\Entity\Workshop;

use App\Interfaces\UuidPrimaryKeyInterface;
use App\Repository\Workshop\PlaceTranslationRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

#[ORM\Table(name: 'places_translation')]
#[ORM\Index(columns: ['locale'], name: 'places_locale_idx')]
#[ORM\Entity(repositoryClass: PlaceTranslationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class PlaceTranslation implements UuidPrimaryKeyInterface, TranslationInterface, TimestampableInterface
{
    use UuidPrimaryKeyTrait;
    use TranslationTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title;

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
}

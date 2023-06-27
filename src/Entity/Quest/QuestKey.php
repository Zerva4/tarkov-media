<?php

namespace App\Entity\Quest;

use App\Entity\Item\Item;
use App\Entity\Map;
use App\Interfaces\Item\ItemInterface;
use App\Interfaces\MapInterface;
use App\Interfaces\Quest\QuestInterface;
use App\Interfaces\Quest\QuestKeyInterface;
use App\Interfaces\UuidPrimaryKeyInterface;
use App\Repository\Quest\QuestKeyRepository;
use App\Traits\UuidPrimaryKeyTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Table(name: 'quests_keys')]
#[ORM\Entity(repositoryClass: QuestKeyRepository::class)]
class QuestKey implements UuidPrimaryKeyInterface, TimestampableInterface, QuestKeyInterface
{
    use UuidPrimaryKeyTrait;
    use TimestampableTrait;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'questsKeys')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'DELETE')]
    private ?ItemInterface $key;

    #[ORM\ManyToOne(targetEntity: Map::class, inversedBy: 'quests')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'DELETE')]
    private ?MapInterface $map = null;

    #[ORM\ManyToOne(targetEntity: Quest::class, inversedBy: 'neededKeys')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?QuestInterface $quest = null;

    public function getKey(): ItemInterface
    {
        return $this->key;
    }

    public function setKey(?ItemInterface $key): QuestKeyInterface
    {
        $this->key = $key;
        $key->addKeyQuest($this);

        return $this;
    }

    public function getMap(): ?MapInterface
    {
        return $this->map;
    }

    public function setMap(?MapInterface $map): QuestKeyInterface
    {
        $this->map = $map;
        $map->addQuestsKey($this);

        return $this;
    }

    public function getQuest(): ?QuestInterface
    {
        return $this->quest;
    }

    public function setQuest(?QuestInterface $quest): QuestKeyInterface
    {
        $this->quest = $quest;

        return $this;
    }

    public function __toString(): string
    {
        return $this->key->__get('title');
    }
}

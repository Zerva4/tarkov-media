<?php

declare(strict_types=1);

namespace App\Entity\Article;

use App\Interfaces\Article\ArticleCategoryInterface;
use App\Interfaces\Article\ArticleInterface;
use App\Repository\Article\ArticleRepository;
use App\Traits\SlugTrait;
use App\Traits\TranslatableMagicMethodsTrait;
use App\Traits\UuidPrimaryKeyTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Table(name: 'articles')]
#[ORM\Index(columns: ['slug'], name: 'articles_slug_idx')]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
/**
 * @Vich\Uploadable
 */
class Article implements ArticleInterface, TranslatableInterface, TimestampableInterface
{
    use UuidPrimaryKeyTrait;
    use TimestampableTrait;
    use SlugTrait;
    use TranslatableTrait;
    use TranslatableMagicMethodsTrait;

    public const STATUS_DRAFT = 0;
    public const STATUS_TO_PUBLISH = 1;
    public const STATUS_PUBLISHED = 2;
    public const STATUS_ARCHIVED = 3;

    #[ORM\Column(type: 'integer', options: ['default' => 0, 'comment' => 'Статус'])]
    private ?int $status = self::STATUS_DRAFT;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePoster = null;

    #[Vich\UploadableField(mapping: 'articles', fileNameProperty: 'imagePoster')]
    #[Assert\Valid]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpg', 'image/gif', 'image/jpeg', 'image/png']
    )]
    /**
     * @Vich\UploadableField(mapping="articles", fileNameProperty="imagePoster")
     * @Assert\Valid
     * @Assert\File(
     *     maxSize="2M",
     *     mimeTypes={
     *         "image/jpg", "image/gif", "image/jpeg", "image/png"
     *     }
     * )
     */
    private ?File $imageFile = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['default' => 1, 'comment' => 'Сложность'])]
    private ?int $complexity;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?DateTimeInterface $readingDuration;

    #[ORM\ManyToOne(targetEntity: ArticleCategory::class, cascade: ['persist'], inversedBy: 'articles')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?ArticleCategoryInterface $category;

    public function __construct(string $defaultLocation = '%app.default_locale%')
    {
        $this->defaultLocale = $defaultLocation;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(?int $status): ArticleInterface
    {
        $this->status = $status;

        return $this;
    }

    public function getImagePoster(): ?string
    {
        return $this->imagePoster;
    }

    public function setImagePoster(?string $imagePoster): ArticleInterface
    {
        $this->imagePoster = $imagePoster;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): ArticleInterface
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new DateTime('NOW');
        }

        return $this;
    }

    public function getComplexity(): ?int
    {
        return $this->complexity;
    }

    public function setComplexity(?int $complexity): ArticleInterface
    {
        $this->complexity = $complexity;

        return $this;
    }

    public function getReadingDuration(): ?DateTimeInterface
    {
        return $this->readingDuration;
    }

    public function setReadingDuration(?DateTimeInterface $readingDuration): ArticleInterface
    {
        $this->readingDuration = $readingDuration;

        return $this;
    }

    public function getCategory(): ?ArticleCategoryInterface
    {
        return $this->category;
    }

    public function setCategory(?ArticleCategoryInterface $category): ArticleInterface
    {
        $this->category = $category;

        return $this;
    }
}

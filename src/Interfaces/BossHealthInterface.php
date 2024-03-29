<?php

declare(strict_types=1);

namespace App\Interfaces;

interface BossHealthInterface
{
    /**
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * @return bool
     */
    public function getPublished(): bool;

    /**
     * @param bool $published
     * @return BossHealthInterface
     */
    public function setPublished(bool $published): BossHealthInterface;

    /**
     * @return int|null
     */
    public function getMax(): ?int;

    /**
     * @param int|null $max
     * @return BossHealthInterface
     */
    public function setMax(?int $max): BossHealthInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return BossHealthInterface
     */
    public function setName(string $name): BossHealthInterface;

    /**
     * @return BossInterface
     */
    public function getBoss(): BossInterface;

    public function setBoss(?BossInterface $boss): BossHealthInterface;
}
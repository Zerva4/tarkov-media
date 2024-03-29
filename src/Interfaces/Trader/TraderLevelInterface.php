<?php

declare(strict_types=1);

namespace App\Interfaces\Trader;

use Doctrine\Common\Collections\Collection;

/**
 * Interface for TraderLoyalty entity.
 */
interface TraderLevelInterface
{
    /**
     * @return int
     */
    public function getLevel(): int;

    /**
     * @param int $level
     * @return TraderLevelInterface
     */
    public function setLevel(int $level): TraderLevelInterface;

    /**
     * @return int
     */
    public function getRequiredPlayerLevel(): int;

    /**
     * @param int $level
     * @return TraderLevelInterface
     */
    public function setRequiredPlayerLevel(int $level): TraderLevelInterface;

    /**
     * @return float
     */
    public function getRequiredReputation(): float;

    /**
     * @param float $reputation
     * @return TraderLevelInterface
     */
    public function setRequiredReputation(float $reputation): TraderLevelInterface;

    /**
     * @return int
     */
    public function getRequiredSales(): int;

    /**
     * @param int $sales
     * @return TraderLevelInterface
     */
    public function setRequiredSales(int $sales): TraderLevelInterface;

    /**
     * @return TraderInterface
     */
    public function getTrader(): TraderInterface;

    /**
     * @param TraderInterface $trader
     * @return TraderLevelInterface
     */
    public function setTrader(TraderInterface $trader): TraderLevelInterface;

    /**
     * @return Collection
     */
    public function getCashOffers(): Collection;

    /**
     * @param Collection $cashOffers
     * @return TraderLevelInterface
     */
    public function setCashOffers(Collection $cashOffers): TraderLevelInterface;
}
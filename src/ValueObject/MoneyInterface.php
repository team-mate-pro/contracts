<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use Stringable;

/**
 * Contract for Money value objects.
 *
 * Implementations must be immutable and ensure amounts are always positive (> 0).
 */
interface MoneyInterface extends Stringable
{
    /**
     * Get the amount.
     *
     * @return float Must be positive (> 0)
     */
    public function getAmount(): float;

    /**
     * Get the currency.
     *
     * @return Currency
     */
    public function getCurrency(): Currency;

    /**
     * Check if this amount equals another.
     *
     * @param MoneyInterface $other
     *
     * @return bool
     */
    public function equals(MoneyInterface $other): bool;
}

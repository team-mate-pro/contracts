<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * Immutable Money value object.
 *
 * Represents a monetary amount with a specific currency.
 */
readonly class Money implements MoneyInterface
{
    /**
     * @param float $amount Amount in decimal format. Must be positive (> 0).
     * @param Currency $currency The currency of this money amount
     *
     * @throws InvalidArgumentException if amount is zero or negative
     */
    public function __construct(
        public float $amount,
        public Currency $currency,
    ) {
        if ($amount <= 0.0) {
            throw new InvalidArgumentException(
                sprintf('Money amount must be positive, got %.2f', $amount)
            );
        }
    }

    #[Groups([MoneyInterface::class])]
    public function getAmount(): float
    {
        return $this->amount;
    }

    #[Groups([MoneyInterface::class, Currency::class])]
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Convert to string representation with currency symbol.
     *
     * @return string
     */
    public function __toString(): string
    {
        $decimalPlaces = $this->currency->getDecimalPlaces();

        return sprintf(
            '%s%s',
            $this->currency->getSymbol(),
            number_format($this->amount, $decimalPlaces, '.', ',')
        );
    }

    /**
     * Check if this amount equals another.
     *
     * @param MoneyInterface $other
     *
     * @return bool
     */
    public function equals(MoneyInterface $other): bool
    {
        return $this->currency === $other->getCurrency()
            && abs($this->amount - $other->getAmount()) < 0.001;
    }
}

<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Currency;
use TeamMatePro\Contracts\ValueObject\Money;
use TeamMatePro\Contracts\ValueObject\MoneyInterface;

#[CoversClass(Money::class)]
final class MoneyTest extends TestCase
{
    #[Test]
    public function itCreatesMoneyWithValidPositiveAmount(): void
    {
        $money = new Money(19.99, Currency::USD);

        $this->assertSame(19.99, $money->getAmount());
        $this->assertSame(Currency::USD, $money->getCurrency());
        $this->assertInstanceOf(MoneyInterface::class, $money);
    }

    #[Test]
    public function itThrowsExceptionWhenAmountIsZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Money amount must be positive, got 0.00');

        new Money(0.0, Currency::USD);
    }

    #[Test]
    public function itThrowsExceptionWhenAmountIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Money amount must be positive, got -10.50');

        new Money(-10.50, Currency::USD);
    }

    #[Test]
    #[DataProvider('validAmountProvider')]
    public function itAcceptsValidPositiveAmounts(float $amount, Currency $currency): void
    {
        $money = new Money($amount, $currency);

        $this->assertSame($amount, $money->getAmount());
        $this->assertSame($currency, $money->getCurrency());
    }

    #[Test]
    #[DataProvider('toStringProvider')]
    public function itConvertsToString(Money $money, string $expected): void
    {
        $this->assertSame($expected, (string) $money);
        $this->assertSame($expected, $money->__toString());
    }

    #[Test]
    #[DataProvider('equalsProvider')]
    public function itChecksEquality(Money $money1, Money $money2, bool $expected): void
    {
        $this->assertSame($expected, $money1->equals($money2));
        // Test symmetry
        $this->assertSame($expected, $money2->equals($money1));
    }

    #[Test]
    public function itIsImmutable(): void
    {
        $money = new Money(100.00, Currency::USD);

        // Public properties are readonly
        $this->assertSame(100.00, $money->amount);
        $this->assertSame(Currency::USD, $money->currency);
    }

    #[Test]
    public function itHandlesLargeAmounts(): void
    {
        $largeAmount = 999999999.99;
        $money = new Money($largeAmount, Currency::USD);

        $this->assertSame($largeAmount, $money->getAmount());
        $this->assertSame('$999,999,999.99', (string) $money);
    }

    #[Test]
    public function itHandlesSmallestPositiveAmount(): void
    {
        $money = new Money(0.01, Currency::USD);

        $this->assertSame(0.01, $money->getAmount());
        $this->assertSame('$0.01', (string) $money);
    }

    #[Test]
    public function itHandlesZeroDecimalCurrencies(): void
    {
        $money = new Money(1000.0, Currency::JPY);

        $this->assertSame(1000.0, $money->getAmount());
        $this->assertSame('¥1,000', (string) $money);
    }

    #[Test]
    public function itHandlesThreeDecimalCurrencies(): void
    {
        $money = new Money(100.500, Currency::BHD);

        $this->assertSame(100.500, $money->getAmount());
        $this->assertSame('د.ب100.500', (string) $money);
    }

    #[Test]
    public function itHandlesCryptocurrencies(): void
    {
        $money = new Money(1.23456789, Currency::BTC);

        $this->assertSame(1.23456789, $money->getAmount());
        $this->assertSame('₿1.23456789', (string) $money);
    }

    #[Test]
    public function itWorksWithAllCurrencies(): void
    {
        $currencies = [
            Currency::USD, Currency::EUR, Currency::GBP, Currency::JPY,
            Currency::CNY, Currency::INR, Currency::BRL, Currency::RUB,
            Currency::AED, Currency::THB, Currency::SGD, Currency::ZAR,
        ];

        foreach ($currencies as $currency) {
            $money = new Money(100.00, $currency);
            $this->assertSame(100.00, $money->getAmount());
            $this->assertSame($currency, $money->getCurrency());
            $this->assertIsString((string) $money);
        }
    }

    #[Test]
    public function itHandlesFloatingPointPrecision(): void
    {
        $money1 = new Money(0.1 + 0.2, Currency::USD); // 0.30000000000000004 in float
        $money2 = new Money(0.3, Currency::USD);

        // Should be equal despite floating point precision issues
        $this->assertTrue($money1->equals($money2));
    }

    /**
     * @return array<string, array{0: float, 1: Currency}>
     */
    public static function validAmountProvider(): array
    {
        return [
            'USD small amount' => [0.01, Currency::USD],
            'USD regular amount' => [19.99, Currency::USD],
            'USD large amount' => [999999.99, Currency::USD],
            'EUR amount' => [100.50, Currency::EUR],
            'JPY amount' => [1000.0, Currency::JPY],
            'GBP amount' => [50.75, Currency::GBP],
            'BTC tiny amount' => [0.00000001, Currency::BTC],
            'BTC regular amount' => [1.23456789, Currency::BTC],
        ];
    }

    /**
     * @return array<string, array{0: Money, 1: string}>
     */
    public static function toStringProvider(): array
    {
        return [
            'USD format' => [new Money(19.99, Currency::USD), '$19.99'],
            'EUR format' => [new Money(100.50, Currency::EUR), '€100.50'],
            'GBP format' => [new Money(0.99, Currency::GBP), '£0.99'],
            'JPY format (no decimals)' => [new Money(1000.0, Currency::JPY), '¥1,000'],
            'KRW format (no decimals)' => [new Money(50000.0, Currency::KRW), '₩50,000'],
            'BHD format (3 decimals)' => [new Money(100.500, Currency::BHD), 'د.ب100.500'],
            'BTC format (8 decimals)' => [new Money(1.23456789, Currency::BTC), '₿1.23456789'],
            'large amount with commas' => [new Money(1234567.89, Currency::USD), '$1,234,567.89'],
            'INR format' => [new Money(1000.00, Currency::INR), '₹1,000.00'],
            'THB format' => [new Money(500.00, Currency::THB), '฿500.00'],
            'AED format' => [new Money(250.00, Currency::AED), 'د.إ250.00'],
        ];
    }

    /**
     * @return array<string, array{0: Money, 1: Money, 2: bool}>
     */
    public static function equalsProvider(): array
    {
        return [
            'equal same currency same amount' => [
                new Money(100.00, Currency::USD),
                new Money(100.00, Currency::USD),
                true,
            ],
            'not equal same currency different amount' => [
                new Money(100.00, Currency::USD),
                new Money(200.00, Currency::USD),
                false,
            ],
            'not equal different currency same amount' => [
                new Money(100.00, Currency::USD),
                new Money(100.00, Currency::EUR),
                false,
            ],
            'not equal different currency different amount' => [
                new Money(100.00, Currency::USD),
                new Money(200.00, Currency::EUR),
                false,
            ],
            'equal with floating point tolerance' => [
                new Money(0.1 + 0.2, Currency::USD),
                new Money(0.3, Currency::USD),
                true,
            ],
            'equal small amounts' => [
                new Money(0.01, Currency::GBP),
                new Money(0.01, Currency::GBP),
                true,
            ],
            'equal large amounts' => [
                new Money(999999.99, Currency::EUR),
                new Money(999999.99, Currency::EUR),
                true,
            ],
        ];
    }
}

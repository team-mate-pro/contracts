<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Currency;

#[CoversClass(Currency::class)]
final class CurrencyTest extends TestCase
{
    #[Test]
    public function itCanBeCreatedFromEnumCase(): void
    {
        $currency = Currency::USD;

        $this->assertSame('USD', $currency->value);
        $this->assertInstanceOf(Currency::class, $currency);
    }

    #[Test]
    public function itCanBeCreatedFromString(): void
    {
        $currency = Currency::from('EUR');

        $this->assertSame(Currency::EUR, $currency);
        $this->assertSame('EUR', $currency->value);
    }

    #[Test]
    #[DataProvider('currencySymbolProvider')]
    public function itReturnsCorrectSymbol(Currency $currency, string $expectedSymbol): void
    {
        $this->assertSame($expectedSymbol, $currency->getSymbol());
    }

    #[Test]
    #[DataProvider('currencyNameProvider')]
    public function itReturnsCorrectName(Currency $currency, string $expectedName): void
    {
        $this->assertSame($expectedName, $currency->getName());
    }

    #[Test]
    #[DataProvider('currencyDecimalPlacesProvider')]
    public function itReturnsCorrectDecimalPlaces(Currency $currency, int $expectedDecimalPlaces): void
    {
        $this->assertSame($expectedDecimalPlaces, $currency->getDecimalPlaces());
    }

    #[Test]
    public function itHasComprehensiveCurrencyList(): void
    {
        $cases = Currency::cases();

        // Should have over 130 currencies
        $this->assertGreaterThan(130, count($cases));

        // Verify all cases are Currency instances
        foreach ($cases as $currency) {
            $this->assertInstanceOf(Currency::class, $currency);
            $this->assertIsString($currency->value);
        }
    }

    #[Test]
    public function itSupportsMajorCurrencies(): void
    {
        $majorCurrencies = ['USD', 'EUR', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD', 'CNY'];

        foreach ($majorCurrencies as $code) {
            $currency = Currency::from($code);
            $this->assertInstanceOf(Currency::class, $currency);
            $this->assertIsString($currency->getSymbol());
            $this->assertIsString($currency->getName());
            $this->assertIsInt($currency->getDecimalPlaces());
        }
    }

    #[Test]
    public function itSupportsCryptocurrencies(): void
    {
        $cryptos = [Currency::BTC, Currency::ETH, Currency::USDT, Currency::USDC];

        foreach ($cryptos as $crypto) {
            $this->assertInstanceOf(Currency::class, $crypto);
            $this->assertSame(8, $crypto->getDecimalPlaces());
        }
    }

    #[Test]
    public function itCanBeComparedForEquality(): void
    {
        $usd1 = Currency::USD;
        $usd2 = Currency::USD;
        $eur = Currency::EUR;

        $this->assertSame($usd1, $usd2);
        $this->assertNotSame($usd1, $eur);
    }

    #[Test]
    public function itCanBeUsedInMatchExpressions(): void
    {
        $currencies = [Currency::USD, Currency::EUR, Currency::JPY, Currency::GBP];

        foreach ($currencies as $currency) {
            $result = match ($currency) {
                Currency::USD => 'US Dollar',
                Currency::EUR => 'Euro',
                Currency::GBP => 'British Pound',
                default => 'Other Currency',
            };

            $this->assertIsString($result);
        }
    }

    #[Test]
    public function itReturnsSymbolForAllCurrencies(): void
    {
        foreach (Currency::cases() as $currency) {
            $symbol = $currency->getSymbol();
            $this->assertIsString($symbol);
            $this->assertNotEmpty($symbol);
        }
    }

    #[Test]
    public function itReturnsNameForAllCurrencies(): void
    {
        foreach (Currency::cases() as $currency) {
            $name = $currency->getName();
            $this->assertIsString($name);
            $this->assertNotEmpty($name);
        }
    }

    #[Test]
    public function itReturnsDecimalPlacesForAllCurrencies(): void
    {
        foreach (Currency::cases() as $currency) {
            $decimalPlaces = $currency->getDecimalPlaces();
            $this->assertIsInt($decimalPlaces);
            $this->assertGreaterThanOrEqual(0, $decimalPlaces);
            $this->assertLessThanOrEqual(8, $decimalPlaces);
        }
    }

    /**
     * @return array<string, array{0: Currency, 1: string}>
     */
    public static function currencySymbolProvider(): array
    {
        return [
            'USD symbol' => [Currency::USD, '$'],
            'EUR symbol' => [Currency::EUR, '€'],
            'GBP symbol' => [Currency::GBP, '£'],
            'JPY symbol' => [Currency::JPY, '¥'],
            'CHF symbol' => [Currency::CHF, 'CHF'],
            'CAD symbol' => [Currency::CAD, 'C$'],
            'INR symbol' => [Currency::INR, '₹'],
            'BRL symbol' => [Currency::BRL, 'R$'],
            'RUB symbol' => [Currency::RUB, '₽'],
            'TRY symbol' => [Currency::TRY, '₺'],
            'KRW symbol' => [Currency::KRW, '₩'],
            'THB symbol' => [Currency::THB, '฿'],
            'ILS symbol' => [Currency::ILS, '₪'],
            'BTC symbol' => [Currency::BTC, '₿'],
            'ETH symbol' => [Currency::ETH, 'Ξ'],
        ];
    }

    /**
     * @return array<string, array{0: Currency, 1: string}>
     */
    public static function currencyNameProvider(): array
    {
        return [
            'USD name' => [Currency::USD, 'US Dollar'],
            'EUR name' => [Currency::EUR, 'Euro'],
            'GBP name' => [Currency::GBP, 'British Pound Sterling'],
            'JPY name' => [Currency::JPY, 'Japanese Yen'],
            'CHF name' => [Currency::CHF, 'Swiss Franc'],
            'INR name' => [Currency::INR, 'Indian Rupee'],
            'BRL name' => [Currency::BRL, 'Brazilian Real'],
            'CNY name' => [Currency::CNY, 'Chinese Yuan'],
            'KRW name' => [Currency::KRW, 'South Korean Won'],
            'AED name' => [Currency::AED, 'UAE Dirham'],
            'BTC name' => [Currency::BTC, 'Bitcoin'],
            'ETH name' => [Currency::ETH, 'Ethereum'],
        ];
    }

    /**
     * @return array<string, array{0: Currency, 1: int}>
     */
    public static function currencyDecimalPlacesProvider(): array
    {
        return [
            'USD has 2 decimal places' => [Currency::USD, 2],
            'EUR has 2 decimal places' => [Currency::EUR, 2],
            'GBP has 2 decimal places' => [Currency::GBP, 2],
            'JPY has 0 decimal places' => [Currency::JPY, 0],
            'KRW has 0 decimal places' => [Currency::KRW, 0],
            'VND has 0 decimal places' => [Currency::VND, 0],
            'BHD has 3 decimal places' => [Currency::BHD, 3],
            'KWD has 3 decimal places' => [Currency::KWD, 3],
            'JOD has 3 decimal places' => [Currency::JOD, 3],
            'BTC has 8 decimal places' => [Currency::BTC, 8],
            'ETH has 8 decimal places' => [Currency::ETH, 8],
        ];
    }
}

<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Country;

#[CoversClass(Country::class)]
final class CountryTest extends TestCase
{
    #[Test]
    public function allCountriesHaveValidTwoLetterCodes(): void
    {
        foreach (Country::cases() as $country) {
            $this->assertMatchesRegularExpression(
                '/^[A-Z]{2}$/',
                $country->value,
                sprintf('Country %s has invalid code format', $country->name)
            );
        }
    }

    #[Test]
    public function allCountriesHaveNames(): void
    {
        foreach (Country::cases() as $country) {
            $name = $country->getCountryName();
            $this->assertNotEmpty(
                $name,
                sprintf('Country %s has empty name', $country->value)
            );
        }
    }

    #[Test]
    public function itHasExpectedNumberOfCountries(): void
    {
        $countries = Country::cases();

        // ISO 3166-1 alpha-2 has 249 officially assigned codes as of 2024
        $this->assertGreaterThanOrEqual(240, count($countries));
        $this->assertLessThanOrEqual(260, count($countries));
    }

    #[Test]
    #[DataProvider('commonCountriesProvider')]
    public function itHandlesCommonCountries(string $code, string $expectedName): void
    {
        $country = Country::from($code);

        $this->assertSame($code, $country->value);
        $this->assertSame($expectedName, $country->getCountryName());
    }

    #[Test]
    #[DataProvider('unknownCountryCodeProvider')]
    public function tryFromReturnsNullForUnknownCodes(string $code): void
    {
        $this->assertNull(Country::tryFrom($code));
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function unknownCountryCodeProvider(): array
    {
        return [
            'not a country' => ['XX'],
            'empty string' => [''],
            'lowercase' => ['pl'],
            'three letters' => ['POL'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function commonCountriesProvider(): array
    {
        return [
            'Poland' => ['PL', 'Poland'],
            'Germany' => ['DE', 'Germany'],
            'France' => ['FR', 'France'],
            'United Kingdom' => ['GB', 'United Kingdom'],
            'United States' => ['US', 'United States'],
            'Canada' => ['CA', 'Canada'],
            'Japan' => ['JP', 'Japan'],
            'China' => ['CN', 'China'],
            'Australia' => ['AU', 'Australia'],
            'Brazil' => ['BR', 'Brazil'],
            'India' => ['IN', 'India'],
            'Mexico' => ['MX', 'Mexico'],
            'Spain' => ['ES', 'Spain'],
            'Italy' => ['IT', 'Italy'],
            'Netherlands' => ['NL', 'Netherlands'],
        ];
    }
}

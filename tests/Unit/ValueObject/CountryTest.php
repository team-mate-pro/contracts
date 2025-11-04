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
    public function itHasPolandCode(): void
    {
        $poland = Country::PL;

        $this->assertSame('PL', $poland->value);
        $this->assertSame('Poland', $poland->getCountryName());
    }

    #[Test]
    public function itHasUnitedStatesCode(): void
    {
        $unitedStates = Country::US;

        $this->assertSame('US', $unitedStates->value);
        $this->assertSame('United States', $unitedStates->getCountryName());
    }

    #[Test]
    public function itHasGermanyCode(): void
    {
        $germany = Country::DE;

        $this->assertSame('DE', $germany->value);
        $this->assertSame('Germany', $germany->getCountryName());
    }

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
            $this->assertIsString($name);
        }
    }

    #[Test]
    public function itCanBeCreatedFromString(): void
    {
        $poland = Country::from('PL');

        $this->assertSame(Country::PL, $poland);
        $this->assertSame('Poland', $poland->getCountryName());
    }

    #[Test]
    public function tryFromReturnsNullForInvalidCode(): void
    {
        $result = Country::tryFrom('XX');

        $this->assertNull($result);
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

        $this->assertSame($expectedName, $country->getCountryName());
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

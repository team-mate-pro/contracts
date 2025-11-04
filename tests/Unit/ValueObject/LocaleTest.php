<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Country;
use TeamMatePro\Contracts\ValueObject\Locale;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    #[Test]
    public function itCreatesLocaleWithLanguageCodeOnly(): void
    {
        $locale = new Locale('en');

        $this->assertSame('en', $locale->languageCode);
        $this->assertNull($locale->country);
        $this->assertSame('en', $locale->toString());
    }

    #[Test]
    public function itCreatesLocaleWithLanguageCodeAndCountry(): void
    {
        $locale = new Locale('pl', Country::PL);

        $this->assertSame('pl', $locale->languageCode);
        $this->assertSame(Country::PL, $locale->country);
        $this->assertSame('pl_PL', $locale->toString());
    }

    #[Test]
    public function itCreatesFromStringWithLanguageOnly(): void
    {
        $locale = Locale::fromString('en');

        $this->assertSame('en', $locale->languageCode);
        $this->assertNull($locale->country);
    }

    #[Test]
    public function itCreatesFromStringWithLanguageAndCountry(): void
    {
        $locale = Locale::fromString('en_US');

        $this->assertSame('en', $locale->languageCode);
        $this->assertSame(Country::US, $locale->country);
    }

    #[Test]
    public function fromStringIsCaseInsensitive(): void
    {
        $locale1 = Locale::fromString('en_us');
        $locale2 = Locale::fromString('EN_US');
        $locale3 = Locale::fromString('En_Us');

        $this->assertSame('en', $locale1->languageCode);
        $this->assertSame(Country::US, $locale1->country);

        $this->assertSame('en', $locale2->languageCode);
        $this->assertSame(Country::US, $locale2->country);

        $this->assertSame('en', $locale3->languageCode);
        $this->assertSame(Country::US, $locale3->country);
    }

    #[Test]
    public function toStringWorksWithoutCountry(): void
    {
        $locale = new Locale('de');

        $this->assertSame('de', $locale->toString());
        $this->assertSame('de', (string) $locale);
    }

    #[Test]
    public function toStringWorksWithCountry(): void
    {
        $locale = new Locale('de', Country::DE);

        $this->assertSame('de_DE', $locale->toString());
        $this->assertSame('de_DE', (string) $locale);
    }

    #[Test]
    public function toUnderscoreFormatWorks(): void
    {
        $locale1 = new Locale('en');
        $locale2 = new Locale('pl', Country::PL);

        $this->assertSame('en', $locale1->toUnderscoreFormat());
        $this->assertSame('pl_PL', $locale2->toUnderscoreFormat());
    }

    #[Test]
    public function toDashFormatWorks(): void
    {
        $locale1 = new Locale('en');
        $locale2 = new Locale('pl', Country::PL);

        $this->assertSame('en', $locale1->toDashFormat());
        $this->assertSame('pl-PL', $locale2->toDashFormat());
    }

    #[Test]
    public function itThrowsExceptionForInvalidLanguageCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Language code must be a two-letter ISO 639-1 code');

        new Locale('eng');
    }

    #[Test]
    public function itThrowsExceptionForSingleLetterLanguageCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Language code must be a two-letter ISO 639-1 code');

        new Locale('e');
    }

    #[Test]
    public function itThrowsExceptionForUppercaseLanguageCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Language code must be a two-letter ISO 639-1 code');

        new Locale('EN');
    }

    #[Test]
    public function itThrowsExceptionForInvalidCountryCodeInFromString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid country code: XX');

        Locale::fromString('en_XX');
    }

    #[Test]
    public function itThrowsExceptionForInvalidLocaleFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid locale format');

        Locale::fromString('en_US_variant');
    }

    #[Test]
    public function localeIsReadonly(): void
    {
        $locale = new Locale('en', Country::US);

        // Reflection to verify readonly properties
        $reflection = new \ReflectionClass($locale);
        $this->assertTrue($reflection->isReadOnly());
    }

    #[Test]
    #[DataProvider('commonLocalesProvider')]
    public function itHandlesCommonLocales(string $localeString, string $expectedLanguage, ?string $expectedCountry): void
    {
        $locale = Locale::fromString($localeString);

        $this->assertSame($expectedLanguage, $locale->languageCode);

        if ($expectedCountry === null) {
            $this->assertNull($locale->country);
        } else {
            $this->assertSame($expectedCountry, $locale->country?->value);
        }
    }

    /**
     * @return array<string, array{string, string, string|null}>
     */
    public static function commonLocalesProvider(): array
    {
        return [
            'English' => ['en', 'en', null],
            'English US' => ['en_US', 'en', 'US'],
            'English UK' => ['en_GB', 'en', 'GB'],
            'Polish Poland' => ['pl_PL', 'pl', 'PL'],
            'German Germany' => ['de_DE', 'de', 'DE'],
            'French France' => ['fr_FR', 'fr', 'FR'],
            'Spanish Spain' => ['es_ES', 'es', 'ES'],
            'Italian Italy' => ['it_IT', 'it', 'IT'],
            'Japanese Japan' => ['ja_JP', 'ja', 'JP'],
            'Chinese China' => ['zh_CN', 'zh', 'CN'],
        ];
    }

    #[Test]
    #[DataProvider('formatConversionProvider')]
    public function itConvertsFormats(
        string $input,
        string $expectedUnderscore,
        string $expectedDash
    ): void {
        $locale = Locale::fromString($input);

        $this->assertSame($expectedUnderscore, $locale->toUnderscoreFormat());
        $this->assertSame($expectedDash, $locale->toDashFormat());
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function formatConversionProvider(): array
    {
        return [
            'Language only' => ['en', 'en', 'en'],
            'US English' => ['en_US', 'en_US', 'en-US'],
            'Polish' => ['pl_PL', 'pl_PL', 'pl-PL'],
            'German' => ['de_DE', 'de_DE', 'de-DE'],
        ];
    }
}

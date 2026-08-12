<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\FeatureToggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggleStatus;

#[CoversClass(FeatureToggleStatus::class)]
final class FeatureToggleStatusTest extends TestCase
{
    #[Test]
    #[DataProvider('statusValueProvider')]
    public function itExposesTheExpectedBackingValue(string $value, FeatureToggleStatus $status): void
    {
        $this->assertSame($value, $status->value);
    }

    #[Test]
    #[DataProvider('statusValueProvider')]
    public function itCanBeCreatedFromValue(string $value, FeatureToggleStatus $expected): void
    {
        $status = FeatureToggleStatus::from($value);

        $this->assertSame($expected, $status);
    }

    #[Test]
    #[DataProvider('statusValueProvider')]
    public function itCanBeTryCreatedFromValue(string $value, FeatureToggleStatus $expected): void
    {
        $status = FeatureToggleStatus::tryFrom($value);

        $this->assertSame($expected, $status);
    }

    #[Test]
    #[DataProvider('unknownValueProvider')]
    public function itReturnsNullForUnknownValues(string $value): void
    {
        $this->assertNull(FeatureToggleStatus::tryFrom($value));
    }

    /**
     * Guards the enum definition: adding, removing or renaming a case breaks this test.
     *
     * @param list<string> $expected
     */
    #[Test]
    #[DataProvider('enumDefinitionProvider')]
    public function itDefinesExactlyTheExpectedCases(string $property, array $expected): void
    {
        $this->assertSame($expected, array_column(FeatureToggleStatus::cases(), $property));
    }

    /**
     * @return array<string, array{0: string, 1: list<string>}>
     */
    public static function enumDefinitionProvider(): array
    {
        return [
            'case names' => ['name', ['Enabled', 'Disabled', 'Unpaid', 'Unavailable', 'QuoteReached']],
            'backing values' => ['value', ['enabled', 'disabled', 'unpaid', 'unavailable', 'quotaReached']],
        ];
    }

    #[Test]
    #[DataProvider('statusComparisonProvider')]
    public function itCanBeCompared(FeatureToggleStatus $status1, FeatureToggleStatus $status2, bool $shouldBeEqual): void
    {
        if ($shouldBeEqual) {
            $this->assertSame($status1, $status2);
        } else {
            $this->assertNotSame($status1, $status2);
        }
    }

    #[Test]
    #[DataProvider('statusMatchProvider')]
    public function itCanBeUsedInMatchExpression(FeatureToggleStatus $status, string $expected): void
    {
        $result = match ($status) {
            FeatureToggleStatus::Enabled => 'feature is enabled',
            FeatureToggleStatus::Disabled => 'feature is disabled',
            FeatureToggleStatus::Unpaid => 'subscription not paid',
            FeatureToggleStatus::Unavailable => 'feature not available',
            FeatureToggleStatus::QuoteReached => 'quota limit reached',
        };

        $this->assertSame($expected, $result);
    }

    #[Test]
    #[DataProvider('allStatusCasesProvider')]
    public function everyCaseHasANonEmptyBackingValue(FeatureToggleStatus $status): void
    {
        $this->assertNotEmpty($status->value);
    }

    #[Test]
    #[DataProvider('allStatusCasesProvider')]
    public function itPreservesValueAfterSerialization(FeatureToggleStatus $original): void
    {
        $unserialized = unserialize(serialize($original));

        $this->assertSame($original, $unserialized);
    }

    /**
     * @return array<string, array{0: string, 1: FeatureToggleStatus}>
     */
    public static function statusValueProvider(): array
    {
        return [
            'enabled' => ['enabled', FeatureToggleStatus::Enabled],
            'disabled' => ['disabled', FeatureToggleStatus::Disabled],
            'unpaid' => ['unpaid', FeatureToggleStatus::Unpaid],
            'unavailable' => ['unavailable', FeatureToggleStatus::Unavailable],
            'quotaReached' => ['quotaReached', FeatureToggleStatus::QuoteReached],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function unknownValueProvider(): array
    {
        return [
            'unknown label' => ['invalid-status'],
            'empty string' => [''],
            'wrong casing' => ['Enabled'],
            'trailing space' => ['enabled '],
        ];
    }

    /**
     * @return array<string, array{0: FeatureToggleStatus}>
     */
    public static function allStatusCasesProvider(): array
    {
        return [
            'Enabled' => [FeatureToggleStatus::Enabled],
            'Disabled' => [FeatureToggleStatus::Disabled],
            'Unpaid' => [FeatureToggleStatus::Unpaid],
            'Unavailable' => [FeatureToggleStatus::Unavailable],
            'QuoteReached' => [FeatureToggleStatus::QuoteReached],
        ];
    }

    /**
     * @return array<string, array{0: FeatureToggleStatus, 1: FeatureToggleStatus, 2: bool}>
     */
    public static function statusComparisonProvider(): array
    {
        return [
            'same enabled instances' => [FeatureToggleStatus::Enabled, FeatureToggleStatus::Enabled, true],
            'same disabled instances' => [FeatureToggleStatus::Disabled, FeatureToggleStatus::Disabled, true],
            'enabled vs disabled' => [FeatureToggleStatus::Enabled, FeatureToggleStatus::Disabled, false],
            'unpaid vs unavailable' => [FeatureToggleStatus::Unpaid, FeatureToggleStatus::Unavailable, false],
            'quota reached vs enabled' => [FeatureToggleStatus::QuoteReached, FeatureToggleStatus::Enabled, false],
        ];
    }

    /**
     * @return array<string, array{0: FeatureToggleStatus, 1: string}>
     */
    public static function statusMatchProvider(): array
    {
        return [
            'enabled' => [FeatureToggleStatus::Enabled, 'feature is enabled'],
            'disabled' => [FeatureToggleStatus::Disabled, 'feature is disabled'],
            'unpaid' => [FeatureToggleStatus::Unpaid, 'subscription not paid'],
            'unavailable' => [FeatureToggleStatus::Unavailable, 'feature not available'],
            'quota reached' => [FeatureToggleStatus::QuoteReached, 'quota limit reached'],
        ];
    }
}

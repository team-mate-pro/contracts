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
    public function itHasEnabledCase(): void
    {
        $this->assertSame('enabled', FeatureToggleStatus::Enabled->value);
    }

    #[Test]
    public function itHasDisabledCase(): void
    {
        $this->assertSame('disabled', FeatureToggleStatus::Disabled->value);
    }

    #[Test]
    public function itHasUnpaidCase(): void
    {
        $this->assertSame('unpaid', FeatureToggleStatus::Unpaid->value);
    }

    #[Test]
    public function itHasUnavailableCase(): void
    {
        $this->assertSame('unavailable', FeatureToggleStatus::Unavailable->value);
    }

    #[Test]
    public function itHasQuoteReachedCase(): void
    {
        $this->assertSame('quotaReached', FeatureToggleStatus::QuoteReached->value);
    }

    #[Test]
    public function itIsBackedByString(): void
    {
        $this->assertIsString(FeatureToggleStatus::Enabled->value);
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
    public function itReturnsNullForInvalidValueWithTryFrom(): void
    {
        $status = FeatureToggleStatus::tryFrom('invalid-status');

        $this->assertNull($status);
    }

    #[Test]
    #[DataProvider('allStatusCasesProvider')]
    public function itSupportsAllDefinedCases(FeatureToggleStatus $status): void
    {
        $this->assertInstanceOf(FeatureToggleStatus::class, $status);
    }

    #[Test]
    public function itHasExactlyFiveCases(): void
    {
        $cases = FeatureToggleStatus::cases();

        $this->assertCount(5, $cases);
    }

    #[Test]
    public function itHasAllExpectedCases(): void
    {
        $cases = FeatureToggleStatus::cases();
        $caseNames = array_map(fn($case) => $case->name, $cases);

        $this->assertContains('Enabled', $caseNames);
        $this->assertContains('Disabled', $caseNames);
        $this->assertContains('Unpaid', $caseNames);
        $this->assertContains('Unavailable', $caseNames);
        $this->assertContains('QuoteReached', $caseNames);
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
    public function itCanBeUsedInSwitchStatement(): void
    {
        $status = FeatureToggleStatus::QuoteReached;
        $result = '';

        switch ($status) {
            case FeatureToggleStatus::Enabled:
                $result = 'enabled';
                break;
            case FeatureToggleStatus::Disabled:
                $result = 'disabled';
                break;
            case FeatureToggleStatus::Unpaid:
                $result = 'unpaid';
                break;
            case FeatureToggleStatus::Unavailable:
                $result = 'unavailable';
                break;
            case FeatureToggleStatus::QuoteReached:
                $result = 'quota reached';
                break;
        }

        $this->assertSame('quota reached', $result);
    }

    #[Test]
    #[DataProvider('allStatusCasesProvider')]
    public function itCanBeSerializedToString(FeatureToggleStatus $status): void
    {
        $value = $status->value;

        $this->assertIsString($value);
        $this->assertNotEmpty($value);
    }

    #[Test]
    public function itPreservesValueAfterSerialization(): void
    {
        $original = FeatureToggleStatus::Enabled;
        $serialized = serialize($original);
        $unserialized = unserialize($serialized);

        $this->assertSame($original, $unserialized);
        $this->assertSame($original->value, $unserialized->value);
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

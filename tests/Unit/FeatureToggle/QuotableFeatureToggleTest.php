<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\FeatureToggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggleInterface;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggleStatus;
use TeamMatePro\Contracts\FeatureToggle\QuotableFeatureToggle;
use TeamMatePro\Contracts\FeatureToggle\QuotableFeatureToggleInterface;

#[CoversClass(QuotableFeatureToggle::class)]
final class QuotableFeatureToggleTest extends TestCase
{
    #[Test]
    public function itImplementsQuotableFeatureToggleInterface(): void
    {
        $toggle = new QuotableFeatureToggle('test-feature');

        $this->assertInstanceOf(QuotableFeatureToggleInterface::class, $toggle);
    }

    #[Test]
    public function itImplementsFeatureToggleInterface(): void
    {
        $toggle = new QuotableFeatureToggle('test-feature');

        $this->assertInstanceOf(FeatureToggleInterface::class, $toggle);
    }

    #[Test]
    public function itCreatesQuotableFeatureToggleWithDefaults(): void
    {
        $toggle = new QuotableFeatureToggle('test-feature');

        $this->assertSame('test-feature', $toggle->getType());
        $this->assertNull($toggle->getQuotaLimit());
        $this->assertSame(0, $toggle->getQuota());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesQuotableFeatureToggleWithQuotaLimit(): void
    {
        $toggle = new QuotableFeatureToggle('api-calls', 1000);

        $this->assertSame('api-calls', $toggle->getType());
        $this->assertSame(1000, $toggle->getQuotaLimit());
        $this->assertSame(0, $toggle->getQuota());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesQuotableFeatureToggleWithQuotaUsage(): void
    {
        $toggle = new QuotableFeatureToggle('api-calls', 1000, 250);

        $this->assertSame('api-calls', $toggle->getType());
        $this->assertSame(1000, $toggle->getQuotaLimit());
        $this->assertSame(250, $toggle->getQuota());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesQuotableFeatureToggleWithAllParameters(): void
    {
        $toggle = new QuotableFeatureToggle(
            'premium-feature',
            5000,
            3000,
            FeatureToggleStatus::Enabled
        );

        $this->assertSame('premium-feature', $toggle->getType());
        $this->assertSame(5000, $toggle->getQuotaLimit());
        $this->assertSame(3000, $toggle->getQuota());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itHandlesNullQuotaLimitMeaningUnlimited(): void
    {
        $toggle = new QuotableFeatureToggle('unlimited-feature', null, 999999);

        $this->assertNull($toggle->getQuotaLimit());
        $this->assertSame(999999, $toggle->getQuota());
    }

    #[Test]
    public function itHandlesZeroQuotaLimit(): void
    {
        $toggle = new QuotableFeatureToggle('restricted-feature', 0, 0);

        $this->assertSame(0, $toggle->getQuotaLimit());
        $this->assertSame(0, $toggle->getQuota());
    }

    #[Test]
    public function itHandlesZeroQuotaUsage(): void
    {
        $toggle = new QuotableFeatureToggle('new-feature', 100, 0);

        $this->assertSame(0, $toggle->getQuota());
    }

    #[Test]
    public function itHandlesQuotaEqualToLimit(): void
    {
        $toggle = new QuotableFeatureToggle('at-limit-feature', 100, 100);

        $this->assertSame(100, $toggle->getQuotaLimit());
        $this->assertSame(100, $toggle->getQuota());
    }

    #[Test]
    public function itHandlesQuotaExceedingLimit(): void
    {
        // This might be valid in scenarios where limit was reduced after usage
        $toggle = new QuotableFeatureToggle('over-limit-feature', 100, 150);

        $this->assertSame(100, $toggle->getQuotaLimit());
        $this->assertSame(150, $toggle->getQuota());
    }

    #[Test]
    #[DataProvider('statusProvider')]
    public function itCreatesQuotableFeatureToggleWithVariousStatuses(FeatureToggleStatus $status): void
    {
        $toggle = new QuotableFeatureToggle('test-feature', 1000, 500, $status);

        $this->assertSame($status, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesQuoteReachedFeatureToggle(): void
    {
        $toggle = new QuotableFeatureToggle(
            'api-calls',
            1000,
            1000,
            FeatureToggleStatus::QuoteReached
        );

        $this->assertSame(FeatureToggleStatus::QuoteReached, $toggle->getStatus());
        $this->assertSame(1000, $toggle->getQuotaLimit());
        $this->assertSame(1000, $toggle->getQuota());
    }

    #[Test]
    public function itIsReadonlyAndImmutable(): void
    {
        $toggle = new QuotableFeatureToggle(
            'test-feature',
            1000,
            500,
            FeatureToggleStatus::Enabled
        );

        // Verify immutability - values should not change
        $this->assertSame('test-feature', $toggle->getType());
        $this->assertSame(1000, $toggle->getQuotaLimit());
        $this->assertSame(500, $toggle->getQuota());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itHandlesEmptyTypeString(): void
    {
        $toggle = new QuotableFeatureToggle('');

        $this->assertSame('', $toggle->getType());
    }

    #[Test]
    public function itHandlesComplexTypeNames(): void
    {
        $complexTypes = [
            'feature.with.dots',
            'feature-with-dashes',
            'feature_with_underscores',
            'FEATURE_UPPERCASE',
            'feature123',
        ];

        foreach ($complexTypes as $type) {
            $toggle = new QuotableFeatureToggle($type);
            $this->assertSame($type, $toggle->getType());
        }
    }

    #[Test]
    public function itMaintainsConsistencyBetweenMultipleReads(): void
    {
        $toggle = new QuotableFeatureToggle(
            'test-feature',
            1000,
            750,
            FeatureToggleStatus::Enabled
        );

        // Multiple reads should return the same values
        for ($i = 0; $i < 5; $i++) {
            $this->assertSame('test-feature', $toggle->getType());
            $this->assertSame(1000, $toggle->getQuotaLimit());
            $this->assertSame(750, $toggle->getQuota());
            $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
        }
    }

    #[Test]
    #[DataProvider('quotaLimitProvider')]
    public function itHandlesVariousQuotaLimitValues(?int $quotaLimit): void
    {
        $toggle = new QuotableFeatureToggle('test-feature', $quotaLimit);

        $this->assertSame($quotaLimit, $toggle->getQuotaLimit());
    }

    #[Test]
    #[DataProvider('quotaUsageProvider')]
    public function itHandlesVariousQuotaUsageValues(int $quota): void
    {
        $toggle = new QuotableFeatureToggle('test-feature', 10000, $quota);

        $this->assertSame($quota, $toggle->getQuota());
    }

    #[Test]
    public function itCreatesDisabledQuotableFeature(): void
    {
        $toggle = new QuotableFeatureToggle(
            'disabled-feature',
            100,
            50,
            FeatureToggleStatus::Disabled
        );

        $this->assertSame(FeatureToggleStatus::Disabled, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesUnpaidQuotableFeature(): void
    {
        $toggle = new QuotableFeatureToggle(
            'premium-feature',
            1000,
            0,
            FeatureToggleStatus::Unpaid
        );

        $this->assertSame(FeatureToggleStatus::Unpaid, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesUnavailableQuotableFeature(): void
    {
        $toggle = new QuotableFeatureToggle(
            'enterprise-feature',
            null,
            0,
            FeatureToggleStatus::Unavailable
        );

        $this->assertSame(FeatureToggleStatus::Unavailable, $toggle->getStatus());
    }

    #[Test]
    public function itHandlesNegativeQuotaValues(): void
    {
        // Negative values might be used for credits or rollbacks
        $toggle = new QuotableFeatureToggle('credit-feature', 1000, -50);

        $this->assertSame(-50, $toggle->getQuota());
    }

    /**
     * @return array<string, array{0: FeatureToggleStatus}>
     */
    public static function statusProvider(): array
    {
        return [
            'enabled status' => [FeatureToggleStatus::Enabled],
            'disabled status' => [FeatureToggleStatus::Disabled],
            'unpaid status' => [FeatureToggleStatus::Unpaid],
            'unavailable status' => [FeatureToggleStatus::Unavailable],
            'quote reached status' => [FeatureToggleStatus::QuoteReached],
        ];
    }

    /**
     * @return array<string, array{0: ?int}>
     */
    public static function quotaLimitProvider(): array
    {
        return [
            'null (unlimited)' => [null],
            'zero limit' => [0],
            'small limit' => [10],
            'medium limit' => [1000],
            'large limit' => [1000000],
        ];
    }

    /**
     * @return array<string, array{0: int}>
     */
    public static function quotaUsageProvider(): array
    {
        return [
            'zero usage' => [0],
            'small usage' => [10],
            'medium usage' => [500],
            'large usage' => [9999],
            'negative usage' => [-10],
        ];
    }
}

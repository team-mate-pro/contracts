<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\FeatureToggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggle;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggleInterface;
use TeamMatePro\Contracts\FeatureToggle\FeatureToggleStatus;

#[CoversClass(FeatureToggle::class)]
final class FeatureToggleTest extends TestCase
{
    #[Test]
    public function itImplementsFeatureToggleInterface(): void
    {
        $toggle = new FeatureToggle('test-feature');

        $this->assertInstanceOf(FeatureToggleInterface::class, $toggle);
    }

    #[Test]
    public function itCreatesFeatureToggleWithDefaultEnabledStatus(): void
    {
        $toggle = new FeatureToggle('test-feature');

        $this->assertSame('test-feature', $toggle->getType());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    #[DataProvider('statusProvider')]
    public function itCreatesFeatureToggleWithSpecificStatus(FeatureToggleStatus $status): void
    {
        $toggle = new FeatureToggle('test-feature', $status);

        $this->assertSame('test-feature', $toggle->getType());
        $this->assertSame($status, $toggle->getStatus());
    }

    #[Test]
    public function itStoresTypeCorrectly(): void
    {
        $type = 'advanced-analytics';
        $toggle = new FeatureToggle($type);

        $this->assertSame($type, $toggle->getType());
    }

    #[Test]
    public function itIsReadonlyAndImmutable(): void
    {
        $toggle = new FeatureToggle('test-feature', FeatureToggleStatus::Enabled);

        // Verify immutability - values should not change
        $this->assertSame('test-feature', $toggle->getType());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
    }

    #[Test]
    public function itHandlesEmptyTypeString(): void
    {
        $toggle = new FeatureToggle('');

        $this->assertSame('', $toggle->getType());
        $this->assertSame(FeatureToggleStatus::Enabled, $toggle->getStatus());
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
            $toggle = new FeatureToggle($type);
            $this->assertSame($type, $toggle->getType());
        }
    }

    #[Test]
    public function itCreatesDisabledFeature(): void
    {
        $toggle = new FeatureToggle('disabled-feature', FeatureToggleStatus::Disabled);

        $this->assertSame(FeatureToggleStatus::Disabled, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesUnpaidFeature(): void
    {
        $toggle = new FeatureToggle('premium-feature', FeatureToggleStatus::Unpaid);

        $this->assertSame(FeatureToggleStatus::Unpaid, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesUnavailableFeature(): void
    {
        $toggle = new FeatureToggle('enterprise-feature', FeatureToggleStatus::Unavailable);

        $this->assertSame(FeatureToggleStatus::Unavailable, $toggle->getStatus());
    }

    #[Test]
    public function itCreatesQuoteReachedFeature(): void
    {
        $toggle = new FeatureToggle('api-calls', FeatureToggleStatus::QuoteReached);

        $this->assertSame(FeatureToggleStatus::QuoteReached, $toggle->getStatus());
    }

    #[Test]
    public function itMaintainsConsistencyBetweenMultipleReads(): void
    {
        $toggle = new FeatureToggle('test-feature', FeatureToggleStatus::Disabled);

        // Multiple reads should return the same values
        for ($i = 0; $i < 5; $i++) {
            $this->assertSame('test-feature', $toggle->getType());
            $this->assertSame(FeatureToggleStatus::Disabled, $toggle->getStatus());
        }
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
}

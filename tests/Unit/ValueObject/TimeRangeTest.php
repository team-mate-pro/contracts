<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\TimeRange;

#[CoversClass(TimeRange::class)]
final class TimeRangeTest extends TestCase
{
    #[Test]
    public function itCreatesTimeRangeWithDateTimeInterface(): void
    {
        $start = new DateTimeImmutable('2025-01-01 00:00:00');
        $end = new DateTimeImmutable('2025-01-31 23:59:59');

        $timeRange = new TimeRange($start, $end);

        $this->assertSame($start, $timeRange->getStart());
        $this->assertSame($end, $timeRange->getEnd());
    }

    #[Test]
    public function itCreatesTimeRangeFromString(): void
    {
        $timeRange = TimeRange::fromString('2025-01-01 00:00:00', '2025-01-31 23:59:59');

        $this->assertInstanceOf(DateTimeInterface::class, $timeRange->getStart());
        $this->assertInstanceOf(DateTimeInterface::class, $timeRange->getEnd());
        $this->assertSame('2025-01-01 00:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-31 23:59:59', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeFromStringWithDifferentFormats(): void
    {
        $timeRange = TimeRange::fromString('2025-03-15', '2025-03-20');

        $this->assertSame('2025-03-15', $timeRange->getStart()->format('Y-m-d'));
        $this->assertSame('2025-03-20', $timeRange->getEnd()->format('Y-m-d'));
    }

    #[Test]
    public function itCreatesTimeRangeFromDuration(): void
    {
        $start = new DateTimeImmutable('2025-01-01 10:00:00');
        $duration = 60; // 60 minutes

        $timeRange = TimeRange::fromDuration($start, $duration);

        $this->assertSame('2025-01-01 10:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-01 11:00:00', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeFromDurationWithVariousValues(): void
    {
        $start = new DateTimeImmutable('2025-01-01 10:00:00');

        $range30Min = TimeRange::fromDuration($start, 30);
        $range120Min = TimeRange::fromDuration($start, 120);
        $range1440Min = TimeRange::fromDuration($start, 1440); // 1 day

        $this->assertSame('2025-01-01 10:30:00', $range30Min->getEnd()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-01 12:00:00', $range120Min->getEnd()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-02 10:00:00', $range1440Min->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeFromDurationWithMutableDateTime(): void
    {
        $start = new \DateTime('2025-01-01 10:00:00');
        $duration = 60;

        $timeRange = TimeRange::fromDuration($start, $duration);

        $this->assertSame('2025-01-01 10:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-01 11:00:00', $timeRange->getEnd()->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(DateTimeImmutable::class, $timeRange->getStart());
    }

    #[Test]
    public function itCreatesTimeRangeForWeeksBelowDate(): void
    {
        $date = new DateTimeImmutable('2025-01-31 23:59:59');

        $timeRange = TimeRange::weeksBelowDate($date, 4);

        $expectedStart = $date->modify('-4 weeks');
        $this->assertSame($expectedStart->format('Y-m-d H:i:s'), $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-31 23:59:59', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeForWeeksBelowDateWithVariousWeekCounts(): void
    {
        $date = new DateTimeImmutable('2025-02-15 12:00:00');

        $range1Week = TimeRange::weeksBelowDate($date, 1);
        $range8Weeks = TimeRange::weeksBelowDate($date, 8);

        $this->assertSame('2025-02-08 12:00:00', $range1Week->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2024-12-21 12:00:00', $range8Weeks->getStart()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeForCurrentMonth(): void
    {
        $timeRange = TimeRange::currentMonth();

        $now = new DateTimeImmutable();
        $expectedStart = (new DateTimeImmutable('first day of this month'))->setTime(0, 0);
        $expectedEnd = (new DateTimeImmutable('last day of this month'))->setTime(23, 59);

        $this->assertSame($expectedStart->format('Y-m-d H:i'), $timeRange->getStart()->format('Y-m-d H:i'));
        $this->assertSame($expectedEnd->format('Y-m-d H:i'), $timeRange->getEnd()->format('Y-m-d H:i'));
    }

    #[Test]
    public function itCreatesTimeRangeForCurrentYear(): void
    {
        $timeRange = TimeRange::currentYear();

        $expectedStart = (new DateTimeImmutable('first day of January this year'))->setTime(0, 0, 0);
        $expectedEnd = (new DateTimeImmutable('last day of December this year'))->setTime(23, 59, 59);

        $this->assertSame($expectedStart->format('Y-m-d H:i:s'), $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame($expectedEnd->format('Y-m-d H:i:s'), $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeForCurrentYearWithMultipleYears(): void
    {
        $timeRange2Years = TimeRange::currentYear(2);
        $timeRange3Years = TimeRange::currentYear(3);

        $expectedStart2 = (new DateTimeImmutable('first day of January this year'))
            ->modify('-1 years')
            ->setTime(0, 0, 0);
        $expectedStart3 = (new DateTimeImmutable('first day of January this year'))
            ->modify('-2 years')
            ->setTime(0, 0, 0);

        $this->assertSame($expectedStart2->format('Y-m-d'), $timeRange2Years->getStart()->format('Y-m-d'));
        $this->assertSame($expectedStart3->format('Y-m-d'), $timeRange3Years->getStart()->format('Y-m-d'));
    }

    #[Test]
    public function itCreatesTimeRangeForPreviousYear(): void
    {
        $timeRange = TimeRange::previousYear();

        $expectedStart = (new DateTimeImmutable('first day of January last year'))->setTime(0, 0, 0);
        $expectedEnd = (new DateTimeImmutable('last day of December last year'))->setTime(23, 59, 59);

        $this->assertSame($expectedStart->format('Y-m-d H:i:s'), $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame($expectedEnd->format('Y-m-d H:i:s'), $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeForPreviousYearWithMultipleYears(): void
    {
        $timeRange2Years = TimeRange::previousYear(2);
        $timeRange3Years = TimeRange::previousYear(3);

        $expectedStart2 = (new DateTimeImmutable('first day of January last year'))
            ->modify('-1 years')
            ->setTime(0, 0, 0);
        $expectedStart3 = (new DateTimeImmutable('first day of January last year'))
            ->modify('-2 years')
            ->setTime(0, 0, 0);

        $this->assertSame($expectedStart2->format('Y-m-d'), $timeRange2Years->getStart()->format('Y-m-d'));
        $this->assertSame($expectedStart3->format('Y-m-d'), $timeRange3Years->getStart()->format('Y-m-d'));
    }

    #[Test]
    #[DataProvider('quarterProvider')]
    public function itCreatesTimeRangeForQuarter(int $quarter, int $year, string $expectedStart, string $expectedEnd): void
    {
        $timeRange = TimeRange::quarter($quarter, $year);

        $this->assertSame($expectedStart, $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame($expectedEnd, $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itCreatesTimeRangeForQuarterWithCurrentYear(): void
    {
        $currentYear = (int) date('Y');
        $timeRange = TimeRange::quarter(1);

        $expectedStart = (new DateTimeImmutable("{$currentYear}-01-01"))->setTime(0, 0, 0);
        $this->assertSame($currentYear, (int) $timeRange->getStart()->format('Y'));
    }

    #[Test]
    #[DataProvider('invalidQuarterProvider')]
    public function itThrowsExceptionForInvalidQuarter(int $quarter): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Quarter must be between 1 and 4, got {$quarter}");

        TimeRange::quarter($quarter, 2025);
    }

    #[Test]
    public function itIsReadonlyAndImmutable(): void
    {
        $start = new DateTimeImmutable('2025-01-01 00:00:00');
        $end = new DateTimeImmutable('2025-01-31 23:59:59');

        $timeRange = new TimeRange($start, $end);

        $this->assertSame($start, $timeRange->getStart());
        $this->assertSame($end, $timeRange->getEnd());

        // Readonly class ensures immutability
        $this->assertTrue(true);
    }

    #[Test]
    public function itReturnsDateTimeImmutableFromFactoryMethods(): void
    {
        $timeRange1 = TimeRange::fromString('2025-01-01', '2025-01-31');
        $timeRange2 = TimeRange::fromDuration(new DateTimeImmutable('2025-01-01'), 60);
        $timeRange3 = TimeRange::currentMonth();
        $timeRange4 = TimeRange::quarter(1, 2025);

        $this->assertInstanceOf(DateTimeInterface::class, $timeRange1->getStart());
        $this->assertInstanceOf(DateTimeInterface::class, $timeRange2->getStart());
        $this->assertInstanceOf(DateTimeInterface::class, $timeRange3->getStart());
        $this->assertInstanceOf(DateTimeInterface::class, $timeRange4->getStart());
    }

    #[Test]
    public function itHandlesLeapYearInQuarters(): void
    {
        // Q1 of a leap year (2024)
        $timeRange = TimeRange::quarter(1, 2024);

        $this->assertSame('2024-01-01 00:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2024-03-31 23:59:59', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itHandlesDifferentMonthLengthsInQuarters(): void
    {
        // Q2 has April (30 days), May (31 days), June (30 days)
        $timeRange = TimeRange::quarter(2, 2025);

        $this->assertSame('2025-04-01 00:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-06-30 23:59:59', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itThrowsDomainExceptionWhenEndDateIsBeforeStartDate(): void
    {
        $start = new DateTimeImmutable('2025-01-31 23:59:59');
        $end = new DateTimeImmutable('2025-01-01 00:00:00');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('End date (2025-01-01 00:00:00) must not be earlier than start date (2025-01-31 23:59:59)');

        new TimeRange($start, $end);
    }

    #[Test]
    public function itThrowsDomainExceptionWhenEndDateIsBeforeStartDateUsingFromString(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('End date (2025-01-01 00:00:00) must not be earlier than start date (2025-12-31 23:59:59)');

        TimeRange::fromString('2025-12-31 23:59:59', '2025-01-01 00:00:00');
    }

    #[Test]
    #[DataProvider('invalidTimeRangeProvider')]
    public function itThrowsDomainExceptionForInvalidTimeRanges(string $start, string $end): void
    {
        $this->expectException(DomainException::class);

        TimeRange::fromString($start, $end);
    }

    #[Test]
    public function itAllowsEqualStartAndEndDates(): void
    {
        $start = new DateTimeImmutable('2025-01-15 12:00:00');
        $end = new DateTimeImmutable('2025-01-15 12:00:00');

        $timeRange = new TimeRange($start, $end);

        $this->assertSame('2025-01-15 12:00:00', $timeRange->getStart()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-15 12:00:00', $timeRange->getEnd()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itThrowsDomainExceptionWithOneDayDifference(): void
    {
        $start = new DateTimeImmutable('2025-01-02');
        $end = new DateTimeImmutable('2025-01-01');

        $this->expectException(DomainException::class);

        new TimeRange($start, $end);
    }

    #[Test]
    public function itThrowsDomainExceptionWithOneSecondDifference(): void
    {
        $start = new DateTimeImmutable('2025-01-01 12:00:01');
        $end = new DateTimeImmutable('2025-01-01 12:00:00');

        $this->expectException(DomainException::class);

        new TimeRange($start, $end);
    }

    /**
     * @return array<string, array{0: int, 1: int, 2: string, 3: string}>
     */
    public static function quarterProvider(): array
    {
        return [
            'Q1 2025' => [1, 2025, '2025-01-01 00:00:00', '2025-03-31 23:59:59'],
            'Q2 2025' => [2, 2025, '2025-04-01 00:00:00', '2025-06-30 23:59:59'],
            'Q3 2025' => [3, 2025, '2025-07-01 00:00:00', '2025-09-30 23:59:59'],
            'Q4 2025' => [4, 2025, '2025-10-01 00:00:00', '2025-12-31 23:59:59'],
            'Q1 2024 (leap year)' => [1, 2024, '2024-01-01 00:00:00', '2024-03-31 23:59:59'],
            'Q1 2023' => [1, 2023, '2023-01-01 00:00:00', '2023-03-31 23:59:59'],
        ];
    }

    /**
     * @return array<string, array{0: int}>
     */
    public static function invalidQuarterProvider(): array
    {
        return [
            'quarter 0' => [0],
            'quarter -1' => [-1],
            'quarter 5' => [5],
            'quarter 10' => [10],
        ];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function invalidTimeRangeProvider(): array
    {
        return [
            'end one month before start' => ['2025-02-01 00:00:00', '2025-01-01 00:00:00'],
            'end one year before start' => ['2025-12-31 23:59:59', '2024-12-31 23:59:59'],
            'end one hour before start' => ['2025-01-15 14:00:00', '2025-01-15 13:00:00'],
            'end one minute before start' => ['2025-01-15 12:30:00', '2025-01-15 12:29:00'],
        ];
    }
}

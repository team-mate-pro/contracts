<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use InvalidArgumentException;
use Symfony\Component\Serializer\Attribute\Groups;

final readonly class TimeRange implements TimeRangeInterface
{
    public function __construct(private DateTimeInterface $start, private DateTimeInterface $end)
    {
        if ($end < $start) {
            throw new DomainException(
                sprintf(
                    'End date (%s) must not be earlier than start date (%s)',
                    $end->format('Y-m-d H:i:s'),
                    $start->format('Y-m-d H:i:s')
                )
            );
        }
    }

    public static function fromString(string $start, string $end): self
    {
        return new self(new DateTimeImmutable($start), new DateTimeImmutable($end));
    }

    public static function fromDuration(DateTimeInterface $start, int $duration): self
    {
        $start = $start instanceof DateTimeImmutable ? $start : DateTimeImmutable::createFromInterface($start);
        return new self($start, $start->modify("+{$duration} minutes"));
    }

    public static function weeksBelowDate(DateTimeInterface $date, int $weeks): self
    {
        /** @phpstan-ignore-next-line */
        $start = $date->modify("-$weeks weeks");
        return new self($start, $date);
    }

    public static function currentMonth(): self
    {
        return new self((new DateTimeImmutable('first day of this month'))->setTime(0, 0), (new DateTimeImmutable('last day of this month'))->setTime(23, 59));
    }

    public static function currentYear(int $year = 1): self
    {
        $endDate = (new DateTimeImmutable('last day of December this year'))->setTime(23, 59, 59);
        $startDate = (new DateTimeImmutable('first day of January this year'))
            ->modify("-" . ($year - 1) . " years")
            ->setTime(0, 0, 0);

        return new self($startDate, $endDate);
    }

    public static function previousYear(int $count = 1): self
    {
        $endDate = (new DateTimeImmutable('last day of December last year'))->setTime(23, 59, 59);
        $startDate = (new DateTimeImmutable('first day of January last year'))
            ->modify("-" . ($count - 1) . " years")
            ->setTime(0, 0, 0);

        return new self($startDate, $endDate);
    }

    public static function quarter(int $quarter, ?int $year = null): self
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new InvalidArgumentException('Quarter must be between 1 and 4, got ' . $quarter);
        }

        $year = $year ?? (int) date('Y');

        $quarterMonths = [
            1 => ['start' => 1, 'end' => 3],
            2 => ['start' => 4, 'end' => 6],
            3 => ['start' => 7, 'end' => 9],
            4 => ['start' => 10, 'end' => 12],
        ];

        $months = $quarterMonths[$quarter];
        $startDate = (new DateTimeImmutable("{$year}-{$months['start']}-01"))->setTime(0, 0, 0);
        $endDate = (new DateTimeImmutable("{$year}-{$months['end']}-01"))
            ->modify('last day of this month')
            ->setTime(23, 59, 59);

        return new self($startDate, $endDate);
    }

    #[Groups([TimeRangeInterface::class])]
    public function getStart(): DateTimeInterface
    {
        return $this->start;
    }

    #[Groups([TimeRangeInterface::class])]
    public function getEnd(): DateTimeInterface
    {
        return $this->end;
    }
}

<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use DateTimeInterface;

/**
 * Should throw DomainException when end date is later than start date.
 */
interface TimeRangeInterface
{
    public function getStart(): DateTimeInterface;

    public function getEnd(): DateTimeInterface;
}

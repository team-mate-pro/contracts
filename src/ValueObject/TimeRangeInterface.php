<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use DateTimeInterface;

interface TimeRangeInterface
{
    public function getStart(): DateTimeInterface;

    public function getEnd(): DateTimeInterface;
}

<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

interface CoordinatesInterface
{
    public function getLatitude(): float;

    public function getLongitude(): float;
}

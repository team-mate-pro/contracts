<?php

declare(strict_types=1);

namespace Model;

interface CoordinatesInterface
{
    public function getLatitude(): float;

    public function getLongitude(): float;
}
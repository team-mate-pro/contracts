<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Geolocalization;

use TeamMatePro\Contracts\ValueObject\AddressInterface;
use TeamMatePro\Contracts\ValueObject\CoordinatesInterface;

interface CoordinatesFinderInterface
{
    public function findCoordinates(AddressInterface $address): ?CoordinatesInterface;
}

<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use TeamMatePro\Contracts\Model\CoordinatesInterface;

interface AddressInterface
{
    public function getStreet(): ?string;

    public function getCity(): ?string;

    public function getZipCode(): ?string;

    public function getCountry(): ?string;

    public function getCoordinates(): ?CoordinatesInterface;
}

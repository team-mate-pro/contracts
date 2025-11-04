<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use Symfony\Component\Serializer\Attribute\Groups;
use TeamMatePro\Contracts\GpsVehicleTracker\VehicleInterface;

use function implode;

#[Groups([VehicleInterface::class, CoordinatesInterface::class])]
final readonly class Address implements AddressInterface
{
    public function __construct(
        private ?string $street,
        private ?string $city,
        private ?string $zipCode,
        private ?string $country = 'PL',
        private ?CoordinatesInterface $coordinates = null
    ) {
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function isValid(): bool
    {
        return !empty($this->street) && !empty($this->city) && !empty($this->zipCode) && !empty($this->country);
    }

    public function __toString(): string
    {
        $values = array_filter([
            $this->street,
            $this->city,
            $this->zipCode,
            $this->country,
        ]);
        return implode(', ', $values);
    }

    public function getCoordinates(): ?CoordinatesInterface
    {
        return $this->coordinates;
    }
}

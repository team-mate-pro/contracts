<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\GpsVehicleTracker;

use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use TeamMatePro\Contracts\Model\CoordinatesInterface;

#[Groups([VehicleInterface::class, CoordinatesInterface::class])]
final class Vehicle implements VehicleInterface
{
    /**
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly string $id,
        private readonly int $odometerReading,
        private readonly int $speed,
        private readonly DateTimeInterface $date,
        private readonly ?string $displayName = null,
        private readonly ?string $vin = null,
        private readonly ?string $registrationNumber = null,
        private readonly ?CoordinatesInterface $coordinates = null,
        private readonly array $metaData = [],
    ) {
    }

    public function getDisplayName(): string
    {
        return $this->displayName ?? $this->id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function getCoordinates(): ?CoordinatesInterface
    {
        return $this->coordinates;
    }

    public function getOdometerReading(): int
    {
        return $this->odometerReading;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}

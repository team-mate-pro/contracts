<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\GpsVehicleTracker;

use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use TeamMatePro\Contracts\Model\CoordinatesInterface;

#[Groups([VehicleInterface::class, CoordinatesInterface::class])]
readonly class Vehicle implements VehicleInterface
{
    /**
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private string $id,
        private int $odometerReading,
        private int $speed,
        private DateTimeInterface $date,
        private int $fuelLevel = 0,
        private ?string $displayName = null,
        private ?string $vin = null,
        private ?string $registrationNumber = null,
        private ?string $externalId = null,
        private ?CoordinatesInterface $coordinates = null,
        private array $metaData = [],
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

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function getFuelLevel(): int
    {
        return $this->fuelLevel;
    }
}

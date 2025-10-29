<?php

declare(strict_types=1);

namespace GpsVehicleTracker;

use Model\CoordinatesInterface;

final class Vehicle implements VehicleInterface
{

    public function getDisplayName(): string
    {
        // TODO: Implement getDisplayName() method.
    }

    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    public function getMetaData(): array
    {
        // TODO: Implement getMetaData() method.
    }

    public function getVin(): ?string
    {
        // TODO: Implement getVin() method.
    }

    public function getRegistrationNumber(): ?string
    {
        // TODO: Implement getRegistrationNumber() method.
    }

    public function getCoordinates(): ?CoordinatesInterface
    {
        // TODO: Implement getCoordinates() method.
    }

    public function getOdometerReading(): int
    {
        // TODO: Implement getOdometerReading() method.
    }

    public function getSpeed(): int
    {
        // TODO: Implement getSpeed() method.
    }
}
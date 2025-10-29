<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Model;

use DateTimeInterface;

interface VehicleInterface extends IdAware, DisplayNameAware, ExternalIdAware
{
    /**
     * Should return all data passed from the original component
     *
     * @return array<string, mixed>
     */
    public function getMetaData(): array;

    public function getVin(): ?string;

    public function getRegistrationNumber(): ?string;

    public function getCoordinates(): ?CoordinatesInterface;

    public function getOdometerReading(): int;

    public function getSpeed(): int;

    public function getDate(): DateTimeInterface;

    public function getFuelLevel(): int;
}

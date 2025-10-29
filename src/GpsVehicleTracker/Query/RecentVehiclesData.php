<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\GpsVehicleTracker\Query;

use TeamMatePro\Contracts\GpsVehicleTracker\VehicleInterface;

interface RecentVehiclesData
{
    /**
     * Should return recent user data for vehicles associated with the client.
     * ID = should be an entity representation in internal system,
     * externalId = value from API
     *
     * @return \TeamMatePro\Contracts\GpsVehicleTracker\VehicleInterface[]
     */
    public function findRecentData(): array;

    /**
     * @param string $id - internal ID
     * @return VehicleInterface|null
     */
    public function findRecentDataById(string $id): ?VehicleInterface;
}

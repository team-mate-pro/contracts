<?php

declare(strict_types=1);

namespace GpsVehicleTracker\Query;

use GpsVehicleTracker\VehicleInterface;

interface RecentVehiclesData
{
    /**
     * Should return recent user data for vehicles associated with the client.
     *
     * @return VehicleInterface[]
     */
    public function findRecentData(): array;
}
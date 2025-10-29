<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\GpsVehicleTracker\Query;

use TeamMatePro\Contracts\GpsVehicleTracker\VehicleInterface;

final class NullVehicleRepository implements RecentVehiclesData
{
    public function findRecentData(): array
    {
        return [];
    }

    public function findRecentDataById(string $id): ?VehicleInterface
    {
        return null;
    }
}

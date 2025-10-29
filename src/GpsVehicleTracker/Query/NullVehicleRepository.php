<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\GpsVehicleTracker\Query;

final class NullVehicleRepository implements RecentVehiclesData
{
    public function findRecentData(): array
    {
        return [];
    }
}

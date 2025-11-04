<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Serializer\Attribute\Groups;

final readonly class Coordinates implements CoordinatesInterface
{
    public function __construct(
        private ?float $latitude = null,
        private ?float $longitude = null
    ) {
        if ($latitude !== null && !$this->isValidLatitude($latitude)) {
            throw new InvalidArgumentException('Invalid latitude');
        }

        if ($longitude !== null && !$this->isValidLongitude($longitude)) {
            throw new InvalidArgumentException('Invalid longitude');
        }
    }

    #[Groups([CoordinatesInterface::class])]
    public function getLatitude(): float
    {
        return $this->latitude ?? 0.0;
    }

    #[Groups([CoordinatesInterface::class])]
    public function getLongitude(): float
    {
        return $this->longitude ?? 0.0;
    }

    private function isValidLatitude(?float $latitude): bool
    {
        return $latitude === null || ($latitude >= -90.0 && $latitude <= 90.0);
    }

    private function isValidLongitude(?float $longitude): bool
    {
        return $longitude === null || ($longitude >= -180.0 && $longitude <= 180.0);
    }

    public function __toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }
}

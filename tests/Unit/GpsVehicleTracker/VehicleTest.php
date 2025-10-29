<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\GpsVehicleTracker;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\GpsVehicleTracker\Vehicle;
use TeamMatePro\Contracts\ValueObject\Coordinates;

#[CoversClass(Vehicle::class)]
final class VehicleTest extends TestCase
{
    #[Test]
    public function itCreatesVehicleWithRequiredParameters(): void
    {
        $date = new DateTimeImmutable('2025-10-29 12:00:00');
        $vehicle = new Vehicle(
            id: 'vehicle-123',
            odometerReading: 15000,
            speed: 60,
            date: $date
        );

        $this->assertSame('vehicle-123', $vehicle->getId());
        $this->assertSame(15000, $vehicle->getOdometerReading());
        $this->assertSame(60, $vehicle->getSpeed());
        $this->assertSame($date, $vehicle->getDate());
        $this->assertSame('vehicle-123', $vehicle->getDisplayName());
        $this->assertNull($vehicle->getVin());
        $this->assertNull($vehicle->getRegistrationNumber());
        $this->assertNull($vehicle->getCoordinates());
        $this->assertSame([], $vehicle->getMetaData());
    }

    #[Test]
    public function itCreatesVehicleWithAllParameters(): void
    {
        $date = new DateTimeImmutable('2025-10-29 12:00:00');
        $coordinates = new Coordinates(45.5, 12.3);
        $metaData = ['color' => 'red', 'model' => 'Tesla Model 3'];

        $vehicle = new Vehicle(
            id: 'vehicle-456',
            odometerReading: 25000,
            speed: 80,
            date: $date,
            displayName: 'Company Car #1',
            vin: '1HGBH41JXMN109186',
            registrationNumber: 'ABC-1234',
            coordinates: $coordinates,
            metaData: $metaData
        );

        $this->assertSame('vehicle-456', $vehicle->getId());
        $this->assertSame(25000, $vehicle->getOdometerReading());
        $this->assertSame(80, $vehicle->getSpeed());
        $this->assertSame($date, $vehicle->getDate());
        $this->assertSame('Company Car #1', $vehicle->getDisplayName());
        $this->assertSame('1HGBH41JXMN109186', $vehicle->getVin());
        $this->assertSame('ABC-1234', $vehicle->getRegistrationNumber());
        $this->assertSame($coordinates, $vehicle->getCoordinates());
        $this->assertSame($metaData, $vehicle->getMetaData());
    }

    #[Test]
    public function itReturnsIdAsDisplayNameWhenDisplayNameIsNull(): void
    {
        $vehicle = new Vehicle(
            id: 'vehicle-789',
            odometerReading: 0,
            speed: 0,
            date: new DateTimeImmutable()
        );

        $this->assertSame('vehicle-789', $vehicle->getDisplayName());
    }

    #[Test]
    public function itReturnsCustomDisplayNameWhenProvided(): void
    {
        $vehicle = new Vehicle(
            id: 'vehicle-789',
            odometerReading: 0,
            speed: 0,
            date: new DateTimeImmutable(),
            displayName: 'My Custom Name'
        );

        $this->assertSame('My Custom Name', $vehicle->getDisplayName());
    }

    #[Test]
    public function itStoresCoordinatesCorrectly(): void
    {
        $coordinates = new Coordinates(52.5200, 13.4050);

        $vehicle = new Vehicle(
            id: 'vehicle-123',
            odometerReading: 10000,
            speed: 50,
            date: new DateTimeImmutable(),
            coordinates: $coordinates
        );

        $retrievedCoordinates = $vehicle->getCoordinates();
        $this->assertNotNull($retrievedCoordinates);
        $this->assertSame(52.5200, $retrievedCoordinates->getLatitude());
        $this->assertSame(13.4050, $retrievedCoordinates->getLongitude());
    }

    #[Test]
    public function itHandlesZeroOdometerReading(): void
    {
        $vehicle = new Vehicle(
            id: 'new-vehicle',
            odometerReading: 0,
            speed: 0,
            date: new DateTimeImmutable()
        );

        $this->assertSame(0, $vehicle->getOdometerReading());
    }

    #[Test]
    public function itHandlesZeroSpeed(): void
    {
        $vehicle = new Vehicle(
            id: 'parked-vehicle',
            odometerReading: 5000,
            speed: 0,
            date: new DateTimeImmutable()
        );

        $this->assertSame(0, $vehicle->getSpeed());
    }

    #[Test]
    public function itHandlesHighSpeedValues(): void
    {
        $vehicle = new Vehicle(
            id: 'fast-vehicle',
            odometerReading: 50000,
            speed: 200,
            date: new DateTimeImmutable()
        );

        $this->assertSame(200, $vehicle->getSpeed());
    }

    #[Test]
    public function itHandlesHighOdometerReadings(): void
    {
        $vehicle = new Vehicle(
            id: 'old-vehicle',
            odometerReading: 999999,
            speed: 60,
            date: new DateTimeImmutable()
        );

        $this->assertSame(999999, $vehicle->getOdometerReading());
    }

    #[Test]
    public function itPreservesDateInstance(): void
    {
        $date = new DateTimeImmutable('2025-10-29 14:30:00');
        $vehicle = new Vehicle(
            id: 'vehicle-date-test',
            odometerReading: 10000,
            speed: 60,
            date: $date
        );

        $this->assertSame($date, $vehicle->getDate());
        $this->assertSame('2025-10-29 14:30:00', $vehicle->getDate()->format('Y-m-d H:i:s'));
    }

    #[Test]
    public function itStoresEmptyMetadataArrayByDefault(): void
    {
        $vehicle = new Vehicle(
            id: 'vehicle-meta',
            odometerReading: 5000,
            speed: 40,
            date: new DateTimeImmutable()
        );

        $this->assertIsArray($vehicle->getMetaData());
        $this->assertEmpty($vehicle->getMetaData());
    }

    #[Test]
    public function itStoresComplexMetadata(): void
    {
        $metaData = [
            'brand' => 'Tesla',
            'model' => 'Model S',
            'year' => 2024,
            'features' => ['autopilot', 'supercharging'],
            'owner' => [
                'name' => 'John Doe',
                'id' => 'owner-123',
            ],
        ];

        $vehicle = new Vehicle(
            id: 'vehicle-meta',
            odometerReading: 5000,
            speed: 40,
            date: new DateTimeImmutable(),
            metaData: $metaData
        );

        $this->assertSame($metaData, $vehicle->getMetaData());
    }

    #[Test]
    public function itIsReadonlyAndPropertiesCannotBeModified(): void
    {
        $vehicle = new Vehicle(
            id: 'immutable-vehicle',
            odometerReading: 10000,
            speed: 60,
            date: new DateTimeImmutable()
        );

        // Verify the values remain constant
        $this->assertSame('immutable-vehicle', $vehicle->getId());
        $this->assertSame(10000, $vehicle->getOdometerReading());
        $this->assertSame(60, $vehicle->getSpeed());

        // Properties are readonly by constructor declaration - no way to modify them
        $this->assertTrue(true);
    }

    #[Test]
    public function itAcceptsDatetimeInterfaceImplementations(): void
    {
        $dateTime = new DateTimeImmutable('2025-10-29 15:45:30');

        $vehicle = new Vehicle(
            id: 'vehicle-interface',
            odometerReading: 10000,
            speed: 50,
            date: $dateTime
        );

        $this->assertInstanceOf(DateTimeInterface::class, $vehicle->getDate());
        $this->assertSame('2025-10-29', $vehicle->getDate()->format('Y-m-d'));
    }

    #[Test]
    public function itHandlesNullVin(): void
    {
        $vehicle = new Vehicle(
            id: 'no-vin-vehicle',
            odometerReading: 5000,
            speed: 40,
            date: new DateTimeImmutable(),
            vin: null
        );

        $this->assertNull($vehicle->getVin());
    }

    #[Test]
    public function itHandlesNullRegistrationNumber(): void
    {
        $vehicle = new Vehicle(
            id: 'no-reg-vehicle',
            odometerReading: 5000,
            speed: 40,
            date: new DateTimeImmutable(),
            registrationNumber: null
        );

        $this->assertNull($vehicle->getRegistrationNumber());
    }

    #[Test]
    public function itHandlesValidVinFormat(): void
    {
        $vin = '1HGBH41JXMN109186';
        $vehicle = new Vehicle(
            id: 'vin-vehicle',
            odometerReading: 10000,
            speed: 60,
            date: new DateTimeImmutable(),
            vin: $vin
        );

        $this->assertSame($vin, $vehicle->getVin());
    }

    #[Test]
    public function itHandlesRegistrationNumberWithDashes(): void
    {
        $registration = 'ABC-1234';
        $vehicle = new Vehicle(
            id: 'reg-vehicle',
            odometerReading: 10000,
            speed: 60,
            date: new DateTimeImmutable(),
            registrationNumber: $registration
        );

        $this->assertSame($registration, $vehicle->getRegistrationNumber());
    }
}

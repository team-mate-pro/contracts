<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\GpsVehicleTracker\Query;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\GpsVehicleTracker\Query\NullVehicleRepository;
use TeamMatePro\Contracts\GpsVehicleTracker\Query\RecentVehiclesData;

#[CoversClass(NullVehicleRepository::class)]
final class NullVehicleRepositoryTest extends TestCase
{
    #[Test]
    public function itImplementsRecentVehiclesDataInterface(): void
    {
        $repository = new NullVehicleRepository();

        $this->assertInstanceOf(RecentVehiclesData::class, $repository);
    }

    #[Test]
    public function itReturnsEmptyArrayForFindRecentData(): void
    {
        $repository = new NullVehicleRepository();

        $result = $repository->findRecentData();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    #[Test]
    public function itReturnsNullForFindRecentDataById(): void
    {
        $repository = new NullVehicleRepository();

        $result = $repository->findRecentDataById('any-id');

        $this->assertNull($result);
    }

    #[Test]
    public function itReturnsNullForAnyIdValue(): void
    {
        $repository = new NullVehicleRepository();

        $this->assertNull($repository->findRecentDataById('123'));
        $this->assertNull($repository->findRecentDataById('abc-def-ghi'));
        $this->assertNull($repository->findRecentDataById(''));
        $this->assertNull($repository->findRecentDataById('0'));
        $this->assertNull($repository->findRecentDataById('vehicle-12345'));
    }

    #[Test]
    public function itConsistentlyReturnsEmptyArray(): void
    {
        $repository = new NullVehicleRepository();

        // Call multiple times to ensure consistency
        $this->assertEmpty($repository->findRecentData());
        $this->assertEmpty($repository->findRecentData());
        $this->assertEmpty($repository->findRecentData());
    }

    #[Test]
    public function itConsistentlyReturnsNull(): void
    {
        $repository = new NullVehicleRepository();

        // Call multiple times with different IDs to ensure consistency
        $this->assertNull($repository->findRecentDataById('id1'));
        $this->assertNull($repository->findRecentDataById('id2'));
        $this->assertNull($repository->findRecentDataById('id3'));
    }

    #[Test]
    public function itCanBeUsedAsNullObject(): void
    {
        $repository = new NullVehicleRepository();

        // Simulate usage in application code where a repository is expected
        $vehicles = $repository->findRecentData();
        $vehicle = $repository->findRecentDataById('test-id');

        // Should not throw any exceptions
        $this->assertEmpty($vehicles);
        $this->assertNull($vehicle);
    }

    #[Test]
    public function itHandlesMultipleCalls(): void
    {
        $repository = new NullVehicleRepository();

        for ($i = 0; $i < 10; $i++) {
            $this->assertEmpty($repository->findRecentData());
            $this->assertNull($repository->findRecentDataById("id-{$i}"));
        }

        $this->assertTrue(true); // No exception thrown
    }

    #[Test]
    public function itReturnsArrayWithCorrectType(): void
    {
        $repository = new NullVehicleRepository();

        $result = $repository->findRecentData();

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
        $this->assertSame([], $result);
    }

    #[Test]
    public function itWorksAsDefaultImplementation(): void
    {
        // This test demonstrates typical usage as a default/null implementation
        $repository = new NullVehicleRepository();

        // Code that expects RecentVehiclesData should work fine
        $processVehicles = function (RecentVehiclesData $repo): int {
            $vehicles = $repo->findRecentData();
            return count($vehicles);
        };

        $count = $processVehicles($repository);
        $this->assertSame(0, $count);
    }

    #[Test]
    public function itHandlesLongIdStrings(): void
    {
        $repository = new NullVehicleRepository();

        $longId = str_repeat('a', 1000);
        $result = $repository->findRecentDataById($longId);

        $this->assertNull($result);
    }

    #[Test]
    public function itHandlesSpecialCharactersInId(): void
    {
        $repository = new NullVehicleRepository();

        $specialIds = [
            '!@#$%^&*()',
            'id-with-spaces and tabs',
            'id/with/slashes',
            'id\\with\\backslashes',
            'id.with.dots',
            'id,with,commas',
            'id;with;semicolons',
        ];

        foreach ($specialIds as $id) {
            $this->assertNull($repository->findRecentDataById($id));
        }
    }

    #[Test]
    public function itHandlesNumericStringIds(): void
    {
        $repository = new NullVehicleRepository();

        $this->assertNull($repository->findRecentDataById('123'));
        $this->assertNull($repository->findRecentDataById('0'));
        $this->assertNull($repository->findRecentDataById('999999'));
        $this->assertNull($repository->findRecentDataById('-1'));
    }

    #[Test]
    public function itHandlesUuidLikeIds(): void
    {
        $repository = new NullVehicleRepository();

        $uuidLikeIds = [
            '550e8400-e29b-41d4-a716-446655440000',
            '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
            '123e4567-e89b-12d3-a456-426614174000',
        ];

        foreach ($uuidLikeIds as $id) {
            $this->assertNull($repository->findRecentDataById($id));
        }
    }
}

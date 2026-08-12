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
    public function itReturnsEmptyArrayForFindRecentData(): void
    {
        $repository = self::repository();

        $result = $repository->findRecentData();

        $this->assertEmpty($result);
    }

    #[Test]
    public function itReturnsNullForFindRecentDataById(): void
    {
        $repository = self::repository();

        $result = $repository->findRecentDataById('any-id');

        $this->assertNull($result);
    }

    #[Test]
    public function itReturnsNullForAnyIdValue(): void
    {
        $repository = self::repository();

        $this->assertNull($repository->findRecentDataById('123'));
        $this->assertNull($repository->findRecentDataById('abc-def-ghi'));
        $this->assertNull($repository->findRecentDataById(''));
        $this->assertNull($repository->findRecentDataById('0'));
        $this->assertNull($repository->findRecentDataById('vehicle-12345'));
    }

    #[Test]
    public function itConsistentlyReturnsNull(): void
    {
        $repository = self::repository();

        // Call multiple times with different IDs to ensure consistency
        $this->assertNull($repository->findRecentDataById('id1'));
        $this->assertNull($repository->findRecentDataById('id2'));
        $this->assertNull($repository->findRecentDataById('id3'));
    }

    #[Test]
    public function itCanBeUsedAsNullObject(): void
    {
        $repository = self::repository();

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
        $repository = self::repository();

        for ($i = 0; $i < 10; $i++) {
            $this->assertEmpty($repository->findRecentData());
            $this->assertNull($repository->findRecentDataById("id-{$i}"));
        }
    }

    #[Test]
    public function itReturnsArrayWithCorrectType(): void
    {
        $repository = self::repository();

        $result = $repository->findRecentData();

        $this->assertCount(0, $result);
    }

    #[Test]
    public function itWorksAsDefaultImplementation(): void
    {
        // This test demonstrates typical usage as a default/null implementation
        $repository = self::repository();

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
        $repository = self::repository();

        $longId = str_repeat('a', 1000);
        $result = $repository->findRecentDataById($longId);

        $this->assertNull($result);
    }

    #[Test]
    public function itHandlesSpecialCharactersInId(): void
    {
        $repository = self::repository();

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
        $repository = self::repository();

        $this->assertNull($repository->findRecentDataById('123'));
        $this->assertNull($repository->findRecentDataById('0'));
        $this->assertNull($repository->findRecentDataById('999999'));
        $this->assertNull($repository->findRecentDataById('-1'));
    }

    #[Test]
    public function itHandlesUuidLikeIds(): void
    {
        $repository = self::repository();

        $uuidLikeIds = [
            '550e8400-e29b-41d4-a716-446655440000',
            '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
            '123e4567-e89b-12d3-a456-426614174000',
        ];

        foreach ($uuidLikeIds as $id) {
            $this->assertNull($repository->findRecentDataById($id));
        }
    }

    /**
     * Typed as the interface on purpose: these tests pin the null objects contract, not its body.
     */
    private static function repository(): RecentVehiclesData
    {
        return new NullVehicleRepository();
    }
}

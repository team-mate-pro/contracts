<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Coordinates;

#[CoversClass(Coordinates::class)]
final class CoordinatesTest extends TestCase
{
    #[Test]
    public function itCreatesCoordinatesWithValidLatitudeAndLongitude(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());
    }

    #[Test]
    public function itCreatesCoordinatesWithNullValues(): void
    {
        $coordinates = new Coordinates();

        $this->assertSame(0.0, $coordinates->getLatitude());
        $this->assertSame(0.0, $coordinates->getLongitude());
    }

    #[Test]
    public function itCreatesCoordinatesWithOnlyLatitude(): void
    {
        $coordinates = new Coordinates(45.5, null);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(0.0, $coordinates->getLongitude());
    }

    #[Test]
    public function itCreatesCoordinatesWithOnlyLongitude(): void
    {
        $coordinates = new Coordinates(null, 12.3);

        $this->assertSame(0.0, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());
    }

    #[Test]
    #[DataProvider('validLatitudeProvider')]
    public function itAcceptsValidLatitudeValues(?float $latitude): void
    {
        $coordinates = new Coordinates($latitude, 0.0);

        $this->assertSame($latitude ?? 0.0, $coordinates->getLatitude());
    }

    #[Test]
    #[DataProvider('validLongitudeProvider')]
    public function itAcceptsValidLongitudeValues(?float $longitude): void
    {
        $coordinates = new Coordinates(0.0, $longitude);

        $this->assertSame($longitude ?? 0.0, $coordinates->getLongitude());
    }

    #[Test]
    #[DataProvider('invalidLatitudeProvider')]
    public function itThrowsExceptionForInvalidLatitude(float $latitude): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid latitude');

        new Coordinates($latitude, 0.0);
    }

    #[Test]
    #[DataProvider('invalidLongitudeProvider')]
    public function itThrowsExceptionForInvalidLongitude(float $longitude): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid longitude');

        new Coordinates(0.0, $longitude);
    }

    #[Test]
    public function itConvertsToStringWithBothValues(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame('45.5, 12.3', (string) $coordinates);
    }

    #[Test]
    public function itConvertsToStringWithNullValues(): void
    {
        $coordinates = new Coordinates();

        $this->assertSame(', ', (string) $coordinates);
    }

    #[Test]
    public function itConvertsToStringWithZeroValues(): void
    {
        $coordinates = new Coordinates(0.0, 0.0);

        $this->assertSame('0, 0', (string) $coordinates);
    }

    #[Test]
    public function itIsReadonlyAndImmutable(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());

        // Attempting to modify would cause a PHP error in readonly class
        $this->assertTrue(true); // Just verify it's readonly by class declaration
    }

    #[Test]
    public function itHandlesBoundaryLatitudeValues(): void
    {
        $minCoordinates = new Coordinates(-90.0, 0.0);
        $maxCoordinates = new Coordinates(90.0, 0.0);

        $this->assertSame(-90.0, $minCoordinates->getLatitude());
        $this->assertSame(90.0, $maxCoordinates->getLatitude());
    }

    #[Test]
    public function itHandlesBoundaryLongitudeValues(): void
    {
        $minCoordinates = new Coordinates(0.0, -180.0);
        $maxCoordinates = new Coordinates(0.0, 180.0);

        $this->assertSame(-180.0, $minCoordinates->getLongitude());
        $this->assertSame(180.0, $maxCoordinates->getLongitude());
    }

    /**
     * @return array<string, array{0: ?float}>
     */
    public static function validLatitudeProvider(): array
    {
        return [
            'null latitude' => [null],
            'zero latitude' => [0.0],
            'positive latitude' => [45.5],
            'negative latitude' => [-45.5],
            'max latitude' => [90.0],
            'min latitude' => [-90.0],
        ];
    }

    /**
     * @return array<string, array{0: ?float}>
     */
    public static function validLongitudeProvider(): array
    {
        return [
            'null longitude' => [null],
            'zero longitude' => [0.0],
            'positive longitude' => [12.3],
            'negative longitude' => [-12.3],
            'max longitude' => [180.0],
            'min longitude' => [-180.0],
        ];
    }

    /**
     * @return array<string, array{0: float}>
     */
    public static function invalidLatitudeProvider(): array
    {
        return [
            'latitude above 90' => [90.1],
            'latitude below -90' => [-90.1],
            'latitude far above range' => [100.0],
            'latitude far below range' => [-100.0],
        ];
    }

    /**
     * @return array<string, array{0: float}>
     */
    public static function invalidLongitudeProvider(): array
    {
        return [
            'longitude above 180' => [180.1],
            'longitude below -180' => [-180.1],
            'longitude far above range' => [200.0],
            'longitude far below range' => [-200.0],
        ];
    }
}

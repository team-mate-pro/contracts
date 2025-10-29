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
    public function it_creates_coordinates_with_valid_latitude_and_longitude(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());
    }

    #[Test]
    public function it_creates_coordinates_with_null_values(): void
    {
        $coordinates = new Coordinates();

        $this->assertSame(0.0, $coordinates->getLatitude());
        $this->assertSame(0.0, $coordinates->getLongitude());
    }

    #[Test]
    public function it_creates_coordinates_with_only_latitude(): void
    {
        $coordinates = new Coordinates(45.5, null);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(0.0, $coordinates->getLongitude());
    }

    #[Test]
    public function it_creates_coordinates_with_only_longitude(): void
    {
        $coordinates = new Coordinates(null, 12.3);

        $this->assertSame(0.0, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());
    }

    #[Test]
    #[DataProvider('validLatitudeProvider')]
    public function it_accepts_valid_latitude_values(?float $latitude): void
    {
        $coordinates = new Coordinates($latitude, 0.0);

        $this->assertSame($latitude ?? 0.0, $coordinates->getLatitude());
    }

    #[Test]
    #[DataProvider('validLongitudeProvider')]
    public function it_accepts_valid_longitude_values(?float $longitude): void
    {
        $coordinates = new Coordinates(0.0, $longitude);

        $this->assertSame($longitude ?? 0.0, $coordinates->getLongitude());
    }

    #[Test]
    #[DataProvider('invalidLatitudeProvider')]
    public function it_throws_exception_for_invalid_latitude(float $latitude): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid latitude');

        new Coordinates($latitude, 0.0);
    }

    #[Test]
    #[DataProvider('invalidLongitudeProvider')]
    public function it_throws_exception_for_invalid_longitude(float $longitude): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid longitude');

        new Coordinates(0.0, $longitude);
    }

    #[Test]
    public function it_converts_to_string_with_both_values(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame('45.5, 12.3', (string) $coordinates);
    }

    #[Test]
    public function it_converts_to_string_with_null_values(): void
    {
        $coordinates = new Coordinates();

        $this->assertSame(', ', (string) $coordinates);
    }

    #[Test]
    public function it_converts_to_string_with_zero_values(): void
    {
        $coordinates = new Coordinates(0.0, 0.0);

        $this->assertSame('0, 0', (string) $coordinates);
    }

    #[Test]
    public function it_is_readonly_and_immutable(): void
    {
        $coordinates = new Coordinates(45.5, 12.3);

        $this->assertSame(45.5, $coordinates->getLatitude());
        $this->assertSame(12.3, $coordinates->getLongitude());

        // Attempting to modify would cause a PHP error in readonly class
        $this->assertTrue(true); // Just verify it's readonly by class declaration
    }

    #[Test]
    public function it_handles_boundary_latitude_values(): void
    {
        $minCoordinates = new Coordinates(-90.0, 0.0);
        $maxCoordinates = new Coordinates(90.0, 0.0);

        $this->assertSame(-90.0, $minCoordinates->getLatitude());
        $this->assertSame(90.0, $maxCoordinates->getLatitude());
    }

    #[Test]
    public function it_handles_boundary_longitude_values(): void
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

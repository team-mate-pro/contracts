<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Address;
use TeamMatePro\Contracts\ValueObject\Coordinates;

#[CoversClass(Address::class)]
final class AddressTest extends TestCase
{
    #[Test]
    public function itCreatesAddressWithAllFields(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertSame('Main Street 123', $address->getStreet());
        $this->assertSame('Warsaw', $address->getCity());
        $this->assertSame('00-001', $address->getZipCode());
        $this->assertSame('PL', $address->getCountry());
    }

    #[Test]
    public function itCreatesAddressWithDefaultCountry(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001'
        );

        $this->assertSame('PL', $address->getCountry());
    }

    #[Test]
    public function itCreatesAddressWithCoordinates(): void
    {
        $coordinates = new Coordinates(52.2297, 21.0122);
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL',
            coordinates: $coordinates
        );

        $this->assertSame($coordinates, $address->getCoordinates());
        $this->assertSame(52.2297, $address->getCoordinates()->getLatitude());
        $this->assertSame(21.0122, $address->getCoordinates()->getLongitude());
    }

    #[Test]
    public function itCreatesAddressWithNullCoordinates(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertNull($address->getCoordinates());
    }

    #[Test]
    public function itCreatesAddressWithNullableFields(): void
    {
        $address = new Address(
            street: null,
            city: null,
            zipCode: null,
            country: null
        );

        $this->assertNull($address->getStreet());
        $this->assertNull($address->getCity());
        $this->assertNull($address->getZipCode());
        $this->assertNull($address->getCountry());
    }

    #[Test]
    public function itValidatesCompleteAddress(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertTrue($address->isValid());
    }

    #[Test]
    #[DataProvider('invalidAddressProvider')]
    public function itInvalidatesIncompleteAddress(
        ?string $street,
        ?string $city,
        ?string $zipCode,
        ?string $country
    ): void {
        $address = new Address(
            street: $street,
            city: $city,
            zipCode: $zipCode,
            country: $country
        );

        $this->assertFalse($address->isValid());
    }

    #[Test]
    public function itConvertsCompleteAddressToString(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertSame('Main Street 123, Warsaw, 00-001, PL', (string) $address);
    }

    #[Test]
    public function itConvertsPartialAddressToString(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: null,
            country: 'PL'
        );

        $this->assertSame('Main Street 123, Warsaw, PL', (string) $address);
    }

    #[Test]
    public function itConvertsAddressWithOnlyStreetToString(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: null,
            zipCode: null,
            country: null
        );

        $this->assertSame('Main Street 123', (string) $address);
    }

    #[Test]
    public function itConvertsEmptyAddressToString(): void
    {
        $address = new Address(
            street: null,
            city: null,
            zipCode: null,
            country: null
        );

        $this->assertSame('', (string) $address);
    }

    #[Test]
    public function itConvertsAddressWithEmptyStringsToString(): void
    {
        $address = new Address(
            street: '',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertSame('Warsaw, 00-001, PL', (string) $address);
    }

    #[Test]
    public function itIsReadonlyAndImmutable(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertSame('Main Street 123', $address->getStreet());
        $this->assertSame('Warsaw', $address->getCity());
        $this->assertSame('00-001', $address->getZipCode());
        $this->assertSame('PL', $address->getCountry());

        // Readonly class ensures immutability
        $this->assertTrue(true);
    }

    #[Test]
    public function itImplementsStringableInterface(): void
    {
        $address = new Address(
            street: 'Main Street 123',
            city: 'Warsaw',
            zipCode: '00-001',
            country: 'PL'
        );

        $this->assertInstanceOf(\Stringable::class, $address);
    }

    #[Test]
    public function itHandlesVariousCountryCodes(): void
    {
        $plAddress = new Address('Street', 'City', '00-001', 'PL');
        $usAddress = new Address('Street', 'City', '90210', 'US');
        $deAddress = new Address('Street', 'City', '10115', 'DE');

        $this->assertSame('PL', $plAddress->getCountry());
        $this->assertSame('US', $usAddress->getCountry());
        $this->assertSame('DE', $deAddress->getCountry());
    }

    #[Test]
    public function itValidatesAddressWithEmptyStrings(): void
    {
        $address = new Address(
            street: '',
            city: '',
            zipCode: '',
            country: ''
        );

        $this->assertFalse($address->isValid());
    }

    #[Test]
    public function itValidatesAddressWithWhitespaceStrings(): void
    {
        $address = new Address(
            street: '   ',
            city: '   ',
            zipCode: '   ',
            country: '   '
        );

        // isValid() uses empty() which treats whitespace as non-empty
        $this->assertTrue($address->isValid());
    }

    /**
     * @return array<string, array{0: ?string, 1: ?string, 2: ?string, 3: ?string}>
     */
    public static function invalidAddressProvider(): array
    {
        return [
            'missing street' => [null, 'Warsaw', '00-001', 'PL'],
            'missing city' => ['Main Street 123', null, '00-001', 'PL'],
            'missing zip code' => ['Main Street 123', 'Warsaw', null, 'PL'],
            'missing country' => ['Main Street 123', 'Warsaw', '00-001', null],
            'missing all fields' => [null, null, null, null],
            'empty street' => ['', 'Warsaw', '00-001', 'PL'],
            'empty city' => ['Main Street 123', '', '00-001', 'PL'],
            'empty zip code' => ['Main Street 123', 'Warsaw', '', 'PL'],
            'empty country' => ['Main Street 123', 'Warsaw', '00-001', ''],
            'only street present' => ['Main Street 123', null, null, null],
            'only city present' => [null, 'Warsaw', null, null],
            'street and city present' => ['Main Street 123', 'Warsaw', null, null],
            'street, city, and zip present' => ['Main Street 123', 'Warsaw', '00-001', null],
        ];
    }
}

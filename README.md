# TeamMatePro Contracts

PHP 8.2+ shared interfaces and value objects for TeamMatePro projects.

## Installation

```bash
composer require team-mate-pro/contracts
```

## Usage

```php
use TeamMatePro\Contracts\Model\IdAware;
use TeamMatePro\Contracts\ValueObject\Coordinates;

// Use shared interfaces
class MyEntity implements IdAware {
    public function getId(): string { /* ... */ }
}

// Use value objects
$location = new Coordinates(latitude: 40.7128, longitude: -74.0060);
```

## Development

```bash
make fix          # Auto-fix code styling
make check_fast   # Quick validation (phpcs, phpstan)
make check        # Full CI/CD validation
make tests        # Run all tests
```

Run `make help` for all available commands.

## Requirements

- PHP 8.2+
- PSR-4 autoloading

## License

Proprietary

# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHP 8.2+ contracts library (`team-mate-pro/contracts`) that defines shared interfaces and value objects used across TMP (TeamMatePro) company projects. It follows PSR-4 autoloading with the namespace `TeamMatePro\Contracts\`.

## Architecture

The codebase is organized into domain-specific modules:

### Core Model Interfaces (`src/Model/`)
Base interfaces that define common patterns:
- `IdAware` - Entities with string identifiers
- `NameAware` - Entities with optional names
- `DisplayNameAware` - Entities that must compute display names (e.g., from email if name unavailable)
- `CoordinatesInterface` - Geographic coordinate support (latitude/longitude)

### Value Objects (`src/ValueObject/`)
Immutable data structures with validation:
- `Coordinates` - Readonly implementation of `CoordinatesInterface` with range validation (-90/90 for latitude, -180/180 for longitude)

### GPS Vehicle Tracker Domain (`src/GpsVehicleTracker/`)
Contracts for vehicle tracking systems:
- `VehicleInterface` - Combines `IdAware` and `DisplayNameAware`, includes VIN, registration, coordinates, odometer, and speed
- `Vehicle` - Concrete implementation (currently scaffolded with TODOs)
- `Query/RecentVehiclesData` - Query interface for fetching recent vehicle data

### Empty Modules
- `src/EventPublisher/` - Reserved for future event publishing contracts
- `src/Exception/` - Reserved for shared exception definitions

## Important Conventions

**Namespace Issue**: The existing code has incorrect namespace declarations. Files use short namespaces (`namespace Model;`) instead of the proper PSR-4 namespace (`namespace TeamMatePro\Contracts\Model;`). When adding new files or modifying existing ones, use the correct namespace pattern defined in composer.json.

**Type Safety**: All files use `declare(strict_types=1);` - maintain this for type safety.

**Readonly Pattern**: Value objects like `Coordinates` use PHP 8.2's readonly classes for immutability.

## Development Commands

Install dependencies:
```bash
composer install
```

Update dependencies:
```bash
composer update
```

Validate composer.json:
```bash
composer validate
```

Dump autoloader:
```bash
composer dump-autoload
```

## File Structure Pattern

When adding new contracts, follow this organization:
- Domain-specific interfaces → `src/{DomainName}/`
- Cross-cutting interfaces → `src/Model/`
- Immutable data structures → `src/ValueObject/`
- Query/Command patterns → `src/{DomainName}/Query/` or `src/{DomainName}/Command/`

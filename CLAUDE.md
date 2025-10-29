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

This project uses a comprehensive Makefile system. Run `make help` to see all available commands.

**Note:** Some make commands rely on composer scripts (e.g., `phpcs:fix`, `phpcs:check`) that need to be configured in `composer.json`. If make commands fail with "no commands defined" errors, the required composer scripts may need to be added.

### Quick Reference (Primary Commands)

**Quality Checks & Tests:**
```bash
make check        # [c] Run all checks (phpcs, phpstan, tests) - CI/CD equivalent
make check_fast   # [cf] Fast checks (phpcs_fix, phpcs, phpstan) - skip heavy tests
make fix          # [f] Auto-fix code styling issues
make tests        # [t] Run all tests (currently: unit tests)
```

**Code Styling (PHPCS):**
```bash
make phpcs        # [cs] Check code styling
make phpcs_fix    # [csf] Auto-fix styling issues
make phpcs_file FILE=src/Model/IdAware.php  # Check specific file
make phpcs_fix_file FILE=src/Model/IdAware.php  # Fix specific file
```

**Static Analysis (PHPStan):**
```bash
make phpstan              # [ps] Run PHPStan with cache warmup
make phpstan_analyze      # [psa] Run analysis without cache warmup
make phpstan_clear        # [psc] Clear result cache
make phpstan_file FILE=src/Model/IdAware.php  # Analyze specific file
make phpstan_baseline     # [psb] Generate baseline file
```

**Tests (PHPUnit):**
```bash
make tests_unit           # [tu] Run unit tests
make tests_coverage       # [tcov] Generate HTML coverage report
make tests_file FILE=tests/Unit/ExampleTest.php  # Run specific test file
make tests_filter FILTER=testMethodName  # Run tests matching pattern
make tests_testdox        # [tdox] Run with testdox format
make tests_verbose        # [tv] Run with verbose output
```

**Docker (if needed):**
```bash
make start        # Full start and rebuild containers
make fast         # Fast start (no rebuild)
make stop         # Stop containers
make docker_bash  # [dbash] Open bash in app container
```

**Git Commands (shortcuts):**
```bash
make git_status       # [gs] Show working tree status
make git_diff         # [gd] Show unstaged changes
make git_log          # [gl] Show recent commits
make git_branches     # [gb] List local branches
```

### Composer Commands

For dependency management, use composer directly:
```bash
composer install          # Install dependencies
composer update           # Update dependencies
composer validate         # Validate composer.json
composer dump-autoload    # Regenerate autoloader
```

### Workflow Examples

**Before committing:**
```bash
make fix          # Auto-fix styling
make check_fast   # Quick validation
```

**Full CI/CD validation:**
```bash
make check        # Run all checks that run in CI/CD
```

**Development cycle:**
```bash
make phpstan              # Static analysis
make tests_unit           # Run unit tests
make tests_coverage       # Check coverage
```

## File Structure Pattern

When adding new contracts, follow this organization:
- Domain-specific interfaces → `src/{DomainName}/`
- Cross-cutting interfaces → `src/Model/`
- Immutable data structures → `src/ValueObject/`
- Query/Command patterns → `src/{DomainName}/Query/` or `src/{DomainName}/Command/`

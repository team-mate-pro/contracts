# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Polityka wersjonowania

Wersję uznajemy za wydaną dopiero w momencie jej wdrożenia na środowisko produkcyjne. Do tego czasu zmiany gromadzą się w sekcji Unreleased i po deployu na prod trafiają do jednej wspólnej wersji, której zostaje przypisany numer (MAJOR/MINOR/PATCH wg SemVer) oraz data deployu. Tag gitowy (git tag -a X.Y.Z) ustawiamy na commicie, który faktycznie trafił na prod — nie na branch dev/stage.

## [Unreleased]

## [1.4.0] - 2026-05-27

### Added
- `Result::withItem(array|object $item)` oraz `Result::withCollection(array $collection)` (`src/Collection/`) — jawne mutatory rozróżniające pojedynczy element od kolekcji, ze zwężeniem typu generycznego `Result<…>` w PHPDoc, dla lepszego rozpoznawania danych po stronie konsumentów (np. renderera REST API)
- `Result::getItemType(): 'item'|'collection'` — pozwala konsumentom rozróżnić, czy `Result` zawiera pojedynczy obiekt, czy kolekcję, bez zgadywania na podstawie kształtu `data`
- `tests/Unit/Collection/ResultTest.php` — testy jednostkowe nowych metod `Result`

### Deprecated
- `Result::with($item)` — generyczny setter pozostaje dla kompatybilności wstecznej; nowe użycia powinny korzystać z `withItem()` lub `withCollection()`

### Changed
- `phpstan.neon` — usunięte ręczne wpisy `includes:` dla `phpstan-phpunit` i `phpstan-symfony`; rozszerzenia są teraz rejestrowane automatycznie przez `phpstan/extension-installer` (dodany jako require-dev w 1.3.1)

## [1.3.1] - 2026-05-27

### Added
- Sprawdzanie progu pokrycia testami w skrypcie `composer tests:unit` — wykorzystuje komendę `tmp:tests` z `team-mate-pro/tests-bundle` (próg minimalny: 91%)
- `bin/console` oraz `tests/App/Kernel.php` — minimalna infrastruktura Symfony Console wymagana przez `tmp:tests` do uruchomienia PHPUnit oraz weryfikacji raportu Clover
- Generowanie raportu Clover (`coverage-clover.xml`) w konfiguracji PHPUnit
- Skrypt `composer tests:coverage` — uruchamia PHPUnit z tekstowym raportem pokrycia

## [1.3.0] - 2026-05-08

### Added
- `ReadableFileInterface` (`src/Model/`) — rozszerza `FileInterface` o metodę `getContent(): string`, pozwalając na rozróżnienie pliku z metadanymi (lazy) od pliku z dostępną zawartością bez powielania kontraktu metadanych
- `.gitlab-ci.yml` — pipeline CI/CD oparty o shared templates z `sh/tmp-infra` (static analysis + auto-publish do GitLab Composer registry na `main`)

## [1.2.0] - 2026-03-24

### Added
- Rozszerzone `ResultType`'y dla pełnego pokrycia kodów statusu HTTP

## [1.1.0] - 2026-02-17

### Added
- `administrativeArea` w kontrakcie adresu

## [1.0.0 – 1.0.13] - 2025-10-29 – 2025-11-20

Pierwsza linia wydań biblioteki kontraktów TMP — początkowy scaffolding i sukcesywne dokładanie value objectów oraz wspólnych interfejsów wykorzystywanych w projektach TMP.

### Added
- Initial setup: PSR-4 (`TeamMatePro\Contracts\`), PHP `>=8.2`, PHPUnit, PHPStan, PHP_CodeSniffer, README, skrypt auto-tag/publish, atrybuty serializera
- Value objects i kontrakty: `Email`, `Locale`, kolekcje, paginacja, feature toggle, `TimeRange` (z walidacją i grupami serializacji), `OrderDirection`, `Policy`, `Coordinates` + `CoordinatesFinder`, `AddressAware`, `Money`/`Currency`, `Timestampable`, `KeyValue`, `Paginable`
- Licencja MIT w metadanych pakietu

### Fixed
- Niepoprawne deklaracje przestrzeni nazw (PSR-4) w kilku plikach

[Unreleased]: https://gitlab.team-mate.pl/sh/contracts/-/compare/1.4.0...HEAD
[1.4.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.4.0
[1.3.1]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.3.1
[1.3.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.3.0
[1.2.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.2.0
[1.1.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.1.0

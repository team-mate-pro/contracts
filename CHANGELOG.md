# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Polityka wersjonowania

Wersję uznajemy za wydaną dopiero w momencie jej wdrożenia na środowisko produkcyjne. Do tego czasu zmiany gromadzą się w sekcji Unreleased i po deployu na prod trafiają do jednej wspólnej wersji, której zostaje przypisany numer (MAJOR/MINOR/PATCH wg SemVer) oraz data deployu. Tag gitowy (git tag -a X.Y.Z) ustawiamy na commicie, który faktycznie trafił na prod — nie na branch dev/stage.

## [Unreleased]

## [3.0.0] - 2026-08-11

### Changed (BREAKING)
- `php` w `require`: `>=8.2` → `>=8.3` — porzucone wsparcie PHP 8.2 (od 2025-12 poza aktywnym wsparciem). Paczka jest testowana na 8.3, 8.4 i 8.5; `symfony/serializer` pozostaje na `^7.0`, bo jedyne użycie komponentu to atrybut `Groups`
- `Result<T>`: parametr generyczny ograniczony do `@template T of array<int|string, mixed>|object`; `withItem()`/`withCollection()` zwracają teraz `$this` zamiast przetypowanego `self<array|object>`, a `create()` zwraca `self<array<int|string, mixed>|object>`. Kod korzystający z `Result` bez generyków działa bez zmian; miejsca deklarujące `Result<string>` lub podobny skalar wymagają korekty adnotacji
- `Result::$data` ma teraz natywny typ `array|object` i pozostaje niezainicjalizowane do czasu ustawienia payloadu (`hasContent()` bez zmian). `getResult()` wywołany przed ustawieniem danych rzuca `Error` zamiast zwracać `null`
- `Result` iteruje jako `IteratorAggregate<int|string, mixed>` — wcześniejsza deklaracja `<int, T>` była nieprawdziwa, bo iteracja wydaje wpisy kolekcji, a nie cały payload

### Fixed
- `TimeRange::weeksBelowDate()` — usunięty `@phpstan-ignore-next-line`; data jest konwertowana przez `DateTimeImmutable::createFromInterface()`, więc `modify()` jest wywoływane na typie, który tę metodę faktycznie ma

### Added
- Testy jednostkowe dla `TimeStampAbleTrait` (`tests/Unit/Model/`) — trait nie miał wcześniej żadnego pokrycia

### Changed
- Środowisko deweloperskie i CI przeniesione na PHP 8.5 (`docker/app/Dockerfile`); PHPUnit `^10.5` → `^11.5`, PHPStan 1.x → 2.x. Zestaw testów i analiza statyczna zweryfikowane na 8.3, 8.4 i 8.5 (CI uruchamia je na 8.5)
- Testy oczyszczone z asercji tautologicznych (m.in. `assertInstanceOf` na wartościach o znanym typie, atrapy `assertTrue(true)` zastąpione `expectNotToPerformAssertions()`); testy enumów przepisane na data providery. Analiza statyczna przechodzi na `level: max` bez baseline i bez `ignoreErrors`

## [2.0.0] - 2026-05-27

### Removed (BREAKING)
- `Result::with($item)` (`src/Collection/`) — generyczny setter usunięty wraz z całym `@deprecated` od 1.4.0; należy korzystać z `Result::withItem(array|object)` lub `Result::withCollection(array)`, które są typowo precyzyjne i ustawiają `itemType`

## [1.5.0] - 2026-05-27

### Added
- `Result::map(callable $callback): self` (`src/Collection/`) — zwraca nowy `Result` z danymi przekształconymi przez callback; zachowuje `itemType`, `meta` oraz `errorCode`; nie mutuje oryginału; rzuca `LogicException`, gdy mapowanie kolekcji zwróci wartość nie-array
- `Result::getDataType(): array{fqcn: class-string, shortName: string}|null` — rozpoznaje typ wpisu w payload (pojedynczy obiekt, kolekcja obiektów lub `PaginatedCollection<object>`); zwraca `null` dla danych skalarowych, pustej kolekcji oraz braku danych — pozwala renderom (np. REST) wyciągnąć `type` metadata bez własnej logiki na `get_class`/`reflection`

### Changed
- `Result::withCollection()` — parametr rozluźniony do `array<int|string, mixed>` (akceptuje dowolny array), zwracany typ generyczny ujednolicony do `self<array<string|int, mixed>|object>` (spójnie z `withItem()`), dzięki czemu `map()` może deklarować jeden typ zwrotny niezależnie od gałęzi `item`/`collection`

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

[Unreleased]: https://gitlab.team-mate.pl/sh/contracts/-/compare/2.0.0...HEAD
[2.0.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/2.0.0
[1.5.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.5.0
[1.4.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.4.0
[1.3.1]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.3.1
[1.3.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.3.0
[1.2.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.2.0
[1.1.0]: https://gitlab.team-mate.pl/sh/contracts/-/tags/1.1.0

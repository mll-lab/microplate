# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

[See releases on GitHub](https://github.com/mll-lab/microplate/releases).

## Unreleased

## v5.3.0

### Added

- Support `thecodingmachine/safe:^2`

## v5.2.0

### Added

- Add `isConsecutive()` method to `AbstractMicroplate`

## v5.1.0

### Changed

- Make requirement of `mll-lab/graphql-php-scalars` optional

## v5.0.0

### Added

- Support Laravel 10

### Changed

- Require `mll-lab/graphql-php-scalars:^6`

## v4.3.2

### Fixed

- Clarify return type of `SectionedMicroplate::addSection()` further

## v4.3.1

### Fixed

- Clarify return type of `SectionedMicroplate::addSection()`

## v4.3.0

### Added

- Add `Row96Well` scalar
- Add `Column96Well` scalar

## v4.2.0

### Changed

- Add `Coordinate96Well` cast

## v4.1.0

### Added

- Open `Microplate` and `SectionedMicroplate` for extension

## v4.0.1

### Fixed

- Allow `null` for `$content` in `matchColumn` and `matchRow`

## v4.0.0

### Changed

- Finalize classes without children

## v3.6.0

### Added

- Support `illuminate/support` 9

## v3.5.0

### Added

- Add `Coordinate#toPaddedString()`

## v3.4.0

### Added

- Make `Coordinate::fromString()` more flexible to ignore leading 0 in column numbers, e.g. A01

## v3.3.0

### Added

- Add method `AbstractMicroplate::toWellWithCoordinateMapper()`

## v3.2.1

### Changed

- Deleted incorrect part of php doc in test

## v3.2.0

### Added

- Add methods `AbstractMicroplate::matchRow()` and `AbstractMicroplate::matchColumn()`

## v3.1.0

### Added

- Add ability to have sectioned microplates to group wells

## v3.0.0

### Added

- Add smart methods to class `Microplate`

### Changed

- Change structure of `Microplate::$well` to `Collection<string, TWell|null>`
- Renamed class `MicroPlate` to `Microplate`

### Removed

- Removed compatibility for `illuminate/support:^6`

## v2.0.0

### Added

- Add compatibility for `illuminate/support:^6|^7|^8`

### Removed

- Removed method `getWells()` from class `MicroPlate` - use `wells` property of `MicroPlate` class

## v1.0.0

### Added

- Add class `Coordinate` with diverse calculation methods to ease dealing with microplates at MLL
- Add abstract class `CoordinateSystem` with `CoordinateSystem12Well` and  `CoordinateSystem96Well` as implementation
- Add class `MicroPlate` and methods `addWell` and `getWells`

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

## v3.6.1

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

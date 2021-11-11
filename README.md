# microplate

[![Continuous Integration](https://github.com/mll-lab/microplate/workflows/Continuous%20Integration/badge.svg)](https://github.com/mll-lab/microplate/actions)
[![Code Coverage](https://codecov.io/gh/mll-lab/microplate/branch/master/graph/badge.svg)](https://codecov.io/gh/mll-lab/microplate)

[![Latest Stable Version](https://poser.pugx.org/mll-lab/microplate/v/stable)](https://packagist.org/packages/mll-lab/microplate)
[![Total Downloads](https://poser.pugx.org/mll-lab/microplate/downloads)](https://packagist.org/packages/mll-lab/microplate)

PHP package to easily convert and work with microplate data

## Installation

Install through composer

```sh
composer require mll-lab/microplate
```

## Usage

### Create Coordinate

```php
//define the coordinate system to work with
$coordinateSystem = new CoordinateSystem96Well();

// create by row and column
$coordinate = new Coordinate('C', 7, $coordinateSystem);

// create by string coordinate 
$coordinate = Coordinate::fromString('C7', $coordinateSystem);

// Automatic evaluation if the row and column are valid within the coordinate system
$coordinate = new Coordinate('X', 7, $coordinateSystem);
// throws ´InvalidArgumentException: Expected a row with value of A,B,C,D,E,F,G,H, got X.´
```
### Calculate the numeric position of a Coordinate
```php
$coordinate = new Coordinate('C', 7, new CoordinateSystem96Well());

// calulate the numeric position by providing the FlowDirection
$coordinate->position(FlowDirection::COLUMN()); // 51
$coordinate->position(FlowDirection::ROW()); // 31
```

### Creating a MicroPlate, adding wells, retrieving them

```php
$coordinateSystem = new CoordinateSystem96Well();

$microPlate = new MicroPlate($coordinateSystem);

$microPlateCoordinate1 = new Coordinate('A', 2, $coordinateSystem);
$microPlateCoordinate2 = new Coordinate('A', 3, $coordinateSystem);

$wellContent1 = 'foo';
$microPlate->addWell($microPlateCoordinate1, $wellContent1);

$wellContent2 = 'bar';
$microPlate->addWell($microPlateCoordinate2, $wellContent2);

// retrieve wells from plate
$microPlate->getWells()
```



## Changelog

See [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

See [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## License

This package is licensed using the MIT License.

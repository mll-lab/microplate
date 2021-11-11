<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\MicroPlate;
use PHPUnit\Framework;

class MicroPlateTest extends Framework\TestCase
{
    public function testCanAddAndRetriedWellBasedOnCoordinateSystem(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();

        $microPlate = new MicroPlate($coordinateSystem);

        $microPlateCoordinate1 = new Coordinate('A', 2, $coordinateSystem);
        $microPlateCoordinate2 = new Coordinate('A', 3, $coordinateSystem);

        $wellContent1 = 'foo';
        $microPlate->addWell($microPlateCoordinate1, $wellContent1);

        $wellContent2 = 'bar';
        $microPlate->addWell($microPlateCoordinate2, $wellContent2);

        self::assertEquals([$microPlateCoordinate1, $wellContent1], ($microPlate->wells)[0]);
        self::assertEquals([$microPlateCoordinate2, $wellContent2], ($microPlate->wells)[1]);

        $coordinateWithOtherCoordinateSystem = new Coordinate('A', 2, new CoordinateSystem12Well());
        // @phpstan-ignore-next-line expecting a type error due to mismatching coordinates
        $microPlate->addWell($coordinateWithOtherCoordinateSystem, 'foo');
    }
}

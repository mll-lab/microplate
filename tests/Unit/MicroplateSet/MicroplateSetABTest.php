<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit\MicroplateSet;

use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\MicroplateSet\MicroplateSetAB;
use PHPUnit\Framework\TestCase;

final class MicroplateSetABTest extends TestCase
{
    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem96Well());

        $setPositionHigherThanMax = 193;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-192, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem96Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-192, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem12Well());

        $setPositionHigherThanMax = 25;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-24, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem12Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-24, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }

    /**
     * @dataProvider dataProvider12Well
     */
    public function testSetLocationFromSetPositionFor12Wells(int $position, string $coordinateString, string $plateID): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem12Well());

        $location = $microplateSet->locationFromPosition($position, FlowDirection::COLUMN());
        self::assertSame($location->coordinate->toString(), $coordinateString);
        self::assertSame($location->plateID, $plateID);
    }

    /**
     * @return iterable<array{position: int, coordinateString: string, plateID: string}>
     */
    public static function dataProvider12Well(): iterable
    {
        yield [
            'position' => 1,
            'coordinateString' => 'A1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 2,
            'coordinateString' => 'B1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 3,
            'coordinateString' => 'C1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 12,
            'coordinateString' => 'C4',
            'plateID' => 'A',
        ];
        yield [
            'position' => 13,
            'coordinateString' => 'A1',
            'plateID' => 'B',
        ];
        yield [
            'position' => 24,
            'coordinateString' => 'C4',
            'plateID' => 'B',
        ];
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testSetLocationFromSetPositionFor96Wells(int $position, string $coordinateString, string $plateID): void
    {
        $microplateSet = new MicroplateSetAB(new CoordinateSystem96Well());

        $location = $microplateSet->locationFromPosition($position, FlowDirection::COLUMN());
        self::assertSame($coordinateString, $location->coordinate->toString());
        self::assertSame($plateID, $location->plateID);
    }

    /**
     * @return iterable<array{position: int, coordinateString: string, plateID: string}>
     */
    public static function dataProvider96Well(): iterable
    {
        yield [
            'position' => 1,
            'coordinateString' => 'A1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 2,
            'coordinateString' => 'B1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 3,
            'coordinateString' => 'C1',
            'plateID' => 'A',
        ];
        yield [
            'position' => 12,
            'coordinateString' => 'D2',
            'plateID' => 'A',
        ];
        yield [
            'position' => 13,
            'coordinateString' => 'E2',
            'plateID' => 'A',
        ];
        yield [
            'position' => 96,
            'coordinateString' => 'H12',
            'plateID' => 'A',
        ];
        yield [
            'position' => 97,
            'coordinateString' => 'A1',
            'plateID' => 'B',
        ];
        yield [
            'position' => 192,
            'coordinateString' => 'H12',
            'plateID' => 'B',
        ];
        yield [
            'position' => 191,
            'coordinateString' => 'G12',
            'plateID' => 'B',
        ];
    }
}

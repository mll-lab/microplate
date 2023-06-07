<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit\MicroplateSet;

use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\MicroplateSet\MicroplateSetABCD;
use PHPUnit\Framework\TestCase;

final class MicroplateSetABCDTest extends TestCase
{
    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem96Well());

        $setPositionHigherThanMax = 385;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-384, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem96Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-384, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem12Well());

        $setPositionHigherThanMax = 49;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-48, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem12Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-48, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }

    /**
     * @dataProvider dataProvider12Well
     */
    public function testSetLocationFromSetPositionFor12Wells(int $position, string $coordinateString, string $plateID): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem12Well());

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
            'position' => 48,
            'coordinateString' => 'C4',
            'plateID' => 'D',
        ];
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testSetLocationFromSetPositionFor96Wells(int $position, string $coordinateString, string $plateID): void
    {
        $microplateSet = new MicroplateSetABCD(new CoordinateSystem96Well());

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
            'position' => 384,
            'coordinateString' => 'H12',
            'plateID' => 'D',
        ];
        yield [
            'position' => 383,
            'coordinateString' => 'G12',
            'plateID' => 'D',
        ];
    }
}

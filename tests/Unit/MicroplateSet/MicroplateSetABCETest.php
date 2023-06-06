<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit\MicroplateSet;

use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\MicroplateSet\MicroplateSetABCDE;
use PHPUnit\Framework\TestCase;

final class MicroplateSetABCDETest extends TestCase
{
    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetABCDE(new CoordinateSystem96Well());

        $setPositionHigherThanMax = 481;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-480, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor96WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetABCDE(new CoordinateSystem96Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-480, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooHigh(): void
    {
        $microplateSet = new MicroplateSetABCDE(new CoordinateSystem12Well());

        $setPositionHigherThanMax = 61;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-60, got: {$setPositionHigherThanMax}"));
        $microplateSet->locationFromPosition($setPositionHigherThanMax, FlowDirection::COLUMN());
    }

    public function testSetLocationFromSetPositionFor12WellPlatesOutOfRangeTooLow(): void
    {
        $microplateSet = new MicroplateSetABCDE(new CoordinateSystem12Well());

        $setPositionLowerThanMin = 0;
        self::expectExceptionObject(new \OutOfRangeException("Expected a position between 1-60, got: {$setPositionLowerThanMin}"));
        $microplateSet->locationFromPosition($setPositionLowerThanMin, FlowDirection::COLUMN());
    }
}

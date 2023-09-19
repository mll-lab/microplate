<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\MicroplateSet\MicroplateSetAB;
use Mll\Microplate\MicroplateSet\MicroplateSetABCD;
use Mll\Microplate\MicroplateSet\MicroplateSetABCDE;
use PHPUnit\Framework\TestCase;

final class MicroplateSetTest extends TestCase
{
    public function testPlateCount(): void
    {
        $anyCoordinateSystemWillDo = new CoordinateSystem96Well();

        self::assertSame(MicroplateSetAB::PLATE_COUNT, (new MicroplateSetAB($anyCoordinateSystemWillDo))->plateCount());
        self::assertSame(MicroplateSetABCD::PLATE_COUNT, (new MicroplateSetABCD($anyCoordinateSystemWillDo))->plateCount());
        self::assertSame(MicroplateSetABCDE::PLATE_COUNT, (new MicroplateSetABCDE($anyCoordinateSystemWillDo))->plateCount());
    }
}

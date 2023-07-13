<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use Mll\Microplate\CoordinateSystem;
use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem48Well;
use Mll\Microplate\CoordinateSystem96Well;
use PHPUnit\Framework\TestCase;

final class CoordinateSystemTest extends TestCase
{
    /** @dataProvider firstLast */
    public function testFirstLast(CoordinateSystem $coordinateSystem, string $expectedFirst, string $expectedLast): void
    {
        $actualFirst = $coordinateSystem->first();
        self::assertSame($expectedFirst, $actualFirst->toString());
        self::assertSame($coordinateSystem, $actualFirst->coordinateSystem);

        $actualLast = $coordinateSystem->last();
        self::assertSame($expectedLast, $actualLast->toString());
        self::assertSame($coordinateSystem, $actualLast->coordinateSystem);
    }

    /** @return iterable<array{CoordinateSystem, string, string}> */
    public static function firstLast(): iterable
    {
        yield [new CoordinateSystem12Well(), 'A1', 'C4'];
        yield [new CoordinateSystem48Well(), 'A1', 'F8'];
        yield [new CoordinateSystem96Well(), 'A1', 'H12'];
    }
}
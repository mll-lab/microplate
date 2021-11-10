<?php

declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use InvalidArgumentException;

use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Enums\FlowDirection;
use PHPUnit\Framework;

class CoordinateTest extends Framework\TestCase
{
    /**
     * @dataProvider dataProvider96Well
     */
    public function testCanConstructFromRowAndColumn(string $row, int $column): void
    {
        $coordinate96Well = new Coordinate($row, $column, new CoordinateSystem96Well());

        $this->assertSame($row . $column, $coordinate96Well->__toString());
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testCanConstructFromPosition(string $row, int $column, int $rowFlowPosition, int $columnFlowPosition): void
    {
        // test for Column-FlowDirection
        $coordinates = Coordinate::fromPosition(
            $columnFlowPosition,
            FlowDirection::COLUMN(),
            new CoordinateSystem96Well()
        );
        $this->assertSame($row, $coordinates->row);
        $this->assertSame($column, $coordinates->column);

        // test for Row-FlowDirection
        $coordinates = Coordinate::fromPosition(
            $rowFlowPosition,
            FlowDirection::ROW(),
            new CoordinateSystem96Well()
        );
        $this->assertSame($row, $coordinates->row);
        $this->assertSame($column, $coordinates->column);
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testFromCoordinatesString(string $row, int $column): void
    {
        $coordinates = Coordinate::fromString($row . $column, new CoordinateSystem96Well());
        $this->assertSame($row, $coordinates->row);
        $this->assertSame($column, $coordinates->column);
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testPosition(string $row, int $column, int $rowFlowPosition, int $columnFlowPosition): void
    {
        $coordinates = new Coordinate($row, $column, new CoordinateSystem96Well());
        $this->assertSame($columnFlowPosition, $coordinates->position(FlowDirection::COLUMN()));
        $this->assertSame($rowFlowPosition, $coordinates->position(FlowDirection::ROW()));
    }

    /**
     * @return list<array{row: string, column: int, rowFlowPosition: int, columnFlowPosition: int}>
     */
    public function dataProvider96Well(): array
    {
        return [
            [
                'row'=> 'A',
                'column'=> 1,
                'rowFlowPosition'=> 1,
                'columnFlowPosition'=> 1,

            ],
            [
                'row'=> 'A',
                'column'=> 2,
                'rowFlowPosition'=> 2,
                'columnFlowPosition'=> 9,

            ],
            [
                'row'=> 'A',
                'column'=> 3,
                'rowFlowPosition'=> 3,
                'columnFlowPosition'=> 17,

            ],
            [
                'row'=> 'A',
                'column'=> 12,
                'rowFlowPosition'=> 12,
                'columnFlowPosition'=> 89,

            ],
            [
                'row'=> 'B',
                'column'=> 1,
                'rowFlowPosition'=> 13,
                'columnFlowPosition'=> 2,

            ],
            [
                'row'=> 'C',
                'column'=> 2,
                'rowFlowPosition'=> 26,
                'columnFlowPosition'=> 11,

            ],
            [
                'row'=> 'D',
                'column'=> 6,
                'rowFlowPosition'=> 42,
                'columnFlowPosition'=> 44,

            ],
            [
                'row'=> 'H',
                'column'=> 11,
                'rowFlowPosition'=> 95,
                'columnFlowPosition'=> 88,

            ],
            [
                'row'=> 'H',
                'column'=> 12,
                'rowFlowPosition'=> 96,
                'columnFlowPosition'=> 96,

            ],
            [
                'row'=> 'G',
                'column'=> 12,
                'rowFlowPosition'=> 84,
                'columnFlowPosition'=> 95,

            ],
        ];
    }

    /**
     * @dataProvider invalidRowsOrColumns
     */
    public function testThrowsOnInvalidRowsOrColumns(string $row, int $column): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Coordinate($row, $column, new CoordinateSystem96Well());
    }

    /**
     * @return array<int, array{string, int}>
     */
    public function invalidRowsOrColumns(): array
    {
        return [
            ['X', 2],
            ['B', 0],
            ['B', 13],
            ['B', -1],
            ['B', 1000],
            ['rolf', 2],
        ];
    }

    /**
     * @dataProvider invalidPositions
     */
    public function testThrowsOnInvalidPositions(int $position): void
    {
        $this->expectException(InvalidArgumentException::class);
        Coordinate::fromPosition($position, FlowDirection::COLUMN(), new CoordinateSystem96Well());
    }

    /**
     * @return array<int, array{int}>
     */
    public function invalidPositions(): array
    {
        return [
            [0],
            [-1],
            [97],
            [10000],
        ];
    }

    /**
     * @dataProvider invalidCoordinates
     */
    public function testThrowsOnInvalidCoordinates(string $coordinateString): void
    {
        $this->expectException(InvalidArgumentException::class);
        Coordinate::fromString($coordinateString, new CoordinateSystem96Well());
    }

    /**
     * @return array<int, array{string}>
     */
    public function invalidCoordinates(): array
    {
        return [
            ['A0'],
            ['X3'],
            ['rolf'],
            ['a1'],
        ];
    }
}

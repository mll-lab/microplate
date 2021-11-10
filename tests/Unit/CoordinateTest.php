<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use InvalidArgumentException;
use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem12Well;
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

        self::assertSame($row . $column, (string) $coordinate96Well);
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
        self::assertSame($row, $coordinates->row);
        self::assertSame($column, $coordinates->column);

        // test for Row-FlowDirection
        $coordinates = Coordinate::fromPosition(
            $rowFlowPosition,
            FlowDirection::ROW(),
            new CoordinateSystem96Well()
        );
        self::assertSame($row, $coordinates->row);
        self::assertSame($column, $coordinates->column);
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testFromCoordinatesString(string $row, int $column): void
    {
        $coordinates = Coordinate::fromString($row . $column, new CoordinateSystem96Well());
        self::assertSame($row, $coordinates->row);
        self::assertSame($column, $coordinates->column);
    }

    /**
     * @dataProvider dataProvider96Well
     */
    public function testPosition96Well(string $row, int $column, int $rowFlowPosition, int $columnFlowPosition): void
    {
        $coordinates = new Coordinate($row, $column, new CoordinateSystem96Well());
        self::assertSame($columnFlowPosition, $coordinates->position(FlowDirection::COLUMN()));
        self::assertSame($rowFlowPosition, $coordinates->position(FlowDirection::ROW()));
    }

    /**
     * @return list<array{row: string, column: int, rowFlowPosition: int, columnFlowPosition: int}>
     */
    public function dataProvider96Well(): array
    {
        return [
            [
                'row' => 'A',
                'column' => 1,
                'rowFlowPosition' => 1,
                'columnFlowPosition' => 1,
            ],
            [
                'row' => 'A',
                'column' => 2,
                'rowFlowPosition' => 2,
                'columnFlowPosition' => 9,
            ],
            [
                'row' => 'A',
                'column' => 3,
                'rowFlowPosition' => 3,
                'columnFlowPosition' => 17,
            ],
            [
                'row' => 'A',
                'column' => 12,
                'rowFlowPosition' => 12,
                'columnFlowPosition' => 89,
            ],
            [
                'row' => 'B',
                'column' => 1,
                'rowFlowPosition' => 13,
                'columnFlowPosition' => 2,
            ],
            [
                'row' => 'C',
                'column' => 2,
                'rowFlowPosition' => 26,
                'columnFlowPosition' => 11,
            ],
            [
                'row' => 'D',
                'column' => 6,
                'rowFlowPosition' => 42,
                'columnFlowPosition' => 44,
            ],
            [
                'row' => 'H',
                'column' => 11,
                'rowFlowPosition' => 95,
                'columnFlowPosition' => 88,
            ],
            [
                'row' => 'H',
                'column' => 12,
                'rowFlowPosition' => 96,
                'columnFlowPosition' => 96,
            ],
            [
                'row' => 'G',
                'column' => 12,
                'rowFlowPosition' => 84,
                'columnFlowPosition' => 95,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider12Well
     */
    public function testPosition12Well(string $row, int $column, int $rowFlowPosition, int $columnFlowPosition): void
    {
        $coordinates = new Coordinate($row, $column, new CoordinateSystem12Well());
        self::assertSame($columnFlowPosition, $coordinates->position(FlowDirection::COLUMN()));
        self::assertSame($rowFlowPosition, $coordinates->position(FlowDirection::ROW()));
    }

    /**
     * @return list<array{row: string, column: int, rowFlowPosition: int, columnFlowPosition: int}>
     */
    public function dataProvider12Well(): array
    {
        return [
            [
                'row' => 'A',
                'column' => 1,
                'rowFlowPosition' => 1,
                'columnFlowPosition' => 1,
            ],
            [
                'row' => 'A',
                'column' => 2,
                'rowFlowPosition' => 2,
                'columnFlowPosition' => 4,
            ],
            [
                'row' => 'A',
                'column' => 3,
                'rowFlowPosition' => 3,
                'columnFlowPosition' => 7,
            ],
            [
                'row' => 'A',
                'column' => 4,
                'rowFlowPosition' => 4,
                'columnFlowPosition' => 10,
            ],
            [
                'row' => 'B',
                'column' => 1,
                'rowFlowPosition' => 5,
                'columnFlowPosition' => 2,
            ],
            [
                'row' => 'B',
                'column' => 2,
                'rowFlowPosition' => 6,
                'columnFlowPosition' => 5,
            ],
            [
                'row' => 'B',
                'column' => 3,
                'rowFlowPosition' => 7,
                'columnFlowPosition' => 8,
            ],
            [
                'row' => 'B',
                'column' => 4,
                'rowFlowPosition' => 8,
                'columnFlowPosition' => 11,
            ],
            [
                'row' => 'C',
                'column' => 1,
                'rowFlowPosition' => 9,
                'columnFlowPosition' => 3,
            ],
            [
                'row' => 'C',
                'column' => 2,
                'rowFlowPosition' => 10,
                'columnFlowPosition' => 6,
            ],
            [
                'row' => 'C',
                'column' => 3,
                'rowFlowPosition' => 11,
                'columnFlowPosition' => 9,
            ],
            [
                'row' => 'C',
                'column' => 4,
                'rowFlowPosition' => 12,
                'columnFlowPosition' => 12,
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

<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Arr;
use function count;
use function get_class;
use function implode;
use InvalidArgumentException;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\UnexpectedFlowDirection;
use function Safe\preg_match;

/**
 * @template TCoordinateSystem of CoordinateSystem
 */
class Coordinate
{
    private const MIN_POSITION = 1;

    public string $row;

    public int $column;

    /**
     * @var TCoordinateSystem
     */
    public CoordinateSystem $coordinateSystem;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(string $row, int $column, CoordinateSystem $coordinateSystem)
    {
        $rows = $coordinateSystem->rows();
        if (! in_array($row, $rows, true)) {
            $rowList = implode(',', $rows);
            throw new InvalidArgumentException("Expected a row with value of {$rowList}, got {$row}.");
        }
        $this->row = $row;

        $columns = $coordinateSystem->columns();
        if (! in_array($column, $columns, true)) {
            $columnsList = implode(',', $columns);
            throw new InvalidArgumentException("Expected a column with value of {$columnsList}, got {$column}.");
        }
        $this->column = $column;

        $this->coordinateSystem = $coordinateSystem;
    }

    /**
     * @template TCoord of CoordinateSystem
     *
     * @param TCoord $coordinateSystem
     *
     * @return static<TCoord>
     */
    public static function fromString(string $coordinateString, CoordinateSystem $coordinateSystem): self
    {
        $rows = $coordinateSystem->rows();
        $rowsOptions = implode('|', $rows);

        $columns = [
            ...$coordinateSystem->columns(),
            ...$coordinateSystem->paddedColumns(),
        ];
        $columnsOptions = implode('|', $columns);

        $valid = preg_match(
            "/^({$rowsOptions})({$columnsOptions})\$/",
            $coordinateString,
            $matches
        );

        if (0 === $valid) {
            $firstValidExample = Arr::first($rows) . Arr::first($columns);
            $lastValidExample = Arr::last($rows) . Arr::last($columns);
            $coordinateSystemClass = get_class($coordinateSystem);
            throw new InvalidArgumentException("Expected a coordinate between {$firstValidExample} and {$lastValidExample} for {$coordinateSystemClass}, got: $coordinateString.");
        }

        return new self($matches[1], (int) $matches[2], $coordinateSystem);
    }

    public function toString(): string
    {
        return $this->row . $this->column;
    }

    /**
     * @param TCoordinateSystem $coordinateSystem
     *
     * @return static<TCoordinateSystem>
     */
    public static function fromPosition(int $position, FlowDirection $direction, CoordinateSystem $coordinateSystem): self
    {
        self::assertPositionInRange($coordinateSystem, $position);

        switch ($direction->getValue()) {
            case FlowDirection::COLUMN:
                return new self(
                    $coordinateSystem->rowForColumnFlowPosition($position),
                    $coordinateSystem->columnForColumnFlowPosition($position),
                    $coordinateSystem
                );

            case FlowDirection::ROW:
                return new self(
                    $coordinateSystem->rowForRowFlowPosition($position),
                    $coordinateSystem->columnForRowFlowPosition($position),
                    $coordinateSystem
                );
            // @codeCoverageIgnoreStart all Enums are listed and this should never happen
            default:
                throw new UnexpectedFlowDirection($direction);
            // @codeCoverageIgnoreEnd
        }
    }

    public function position(FlowDirection $direction): int
    {
        /** @var int $rowIndex Must be found, since __construct enforces $this->row is valid */
        $rowIndex = array_search($this->row, $this->coordinateSystem->rows(), true);

        /** @var int $columnIndex Must be found, since __construct enforces $this->column is valid */
        $columnIndex = array_search($this->column, $this->coordinateSystem->columns(), true);

        switch ($direction->getValue()) {
            case FlowDirection::ROW:
                return $rowIndex * count($this->coordinateSystem->columns()) + $columnIndex + 1;
            case FlowDirection::COLUMN:
                return $columnIndex * count($this->coordinateSystem->rows()) + $rowIndex + 1;
            // @codeCoverageIgnoreStart all Enums are listed and this should never happen
            default:
                throw new UnexpectedFlowDirection($direction);
            // @codeCoverageIgnoreEnd
        }
    }

    private static function assertPositionInRange(CoordinateSystem $coordinateSystem, int $position): void
    {
        if (! in_array($position, range(self::MIN_POSITION, $coordinateSystem->positionsCount()), true)) {
            throw new InvalidArgumentException("Expected a position between 1-{$coordinateSystem->positionsCount()}, got: {$position}.");
        }
    }
}

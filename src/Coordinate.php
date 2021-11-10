<?php declare(strict_types=1);

namespace Mll\Microplate;

use InvalidArgumentException;
use Mll\Microplate\Enums\FlowDirection;
use Stringable;
use UnexpectedValueException;

class Coordinate implements Stringable
{
    private const MIN_POSITION = 1;

    public string $row;

    public int $column;

    public CoordinateSystem $coordinateSystem;

    public function __construct(string $row, int $column, CoordinateSystem $coordinateSystem)
    {
        $rowCoordinates = $coordinateSystem->rowCoordinates();
        if (! in_array($row, $rowCoordinates, true)) {
            $rows = implode(',', $rowCoordinates);
            throw new InvalidArgumentException("Expected a row with value of {$rows}, got {$row}.");
        }
        $this->row = $row;

        $columnCoordinates = $coordinateSystem->columnCoordinates();
        if (! in_array($column, $columnCoordinates, true)) {
            $columns = implode(',', $columnCoordinates);
            throw new InvalidArgumentException("Expected a column with value of {$columns}, got {$column}.");
        }
        $this->column = $column;

        $this->coordinateSystem = $coordinateSystem;
    }

    public static function fromString(string $coordinateString, CoordinateSystem $coordinateSystem): self
    {
        $rowCoordinates = $coordinateSystem->rowCoordinates();
        $rowCoordinatesOptions = implode('|', $rowCoordinates);

        $columnCoordinates = $coordinateSystem->columnCoordinates();
        $columnCoordinatesOptions = implode('|', $columnCoordinates);

        $valid = \Safe\preg_match(
            "/^({$rowCoordinatesOptions})({$columnCoordinatesOptions})\$/",
            $coordinateString,
            $matches
        );

        if (0 === $valid) {
            $validExample = $columnCoordinates[0] . $columnCoordinates[0];
            throw new InvalidArgumentException("Expected a coordinate such as {$validExample}, got: {$coordinateString}.");
        }

        return new self($matches[1], (int) $matches[2], $coordinateSystem);
    }

    public function __toString()
    {
        return $this->row . $this->column;
    }

    public static function fromPosition(int $position, FlowDirection $direction, CoordinateSystem $coordinateSystem): self
    {
        self::assertPositionInRange($coordinateSystem, $position);

        switch ($direction->getValue()) {
            case FlowDirection::COLUMN()->getValue():
                return new self(
                    $coordinateSystem->rowForColumnFlowPosition($position),
                    $coordinateSystem->columnForColumnFlowPosition($position),
                    $coordinateSystem
                );

            case FlowDirection::ROW()->getValue():
                return new self(
                    $coordinateSystem->rowForRowFlowPosition($position),
                    $coordinateSystem->columnForRowFlowPosition($position),
                    $coordinateSystem
                );
            // @codeCoverageIgnoreStart all Enums are listed and this should never happen
            default:
                throw new UnexpectedValueException('Unexpected flow direction value:' . $direction->getValue());
            // @codeCoverageIgnoreEnd
        }
    }

    public function position(FlowDirection $direction): int
    {
        /** @var int $rowIndex Must be found, since __construct enforces $this->row is valid */
        $rowIndex = array_search($this->row, $this->coordinateSystem->rowCoordinates(), true);

        /** @var int $columnIndex Must be found, since __construct enforces $this->column is valid */
        $columnIndex = array_search($this->column, $this->coordinateSystem->columnCoordinates(), true);

        switch ($direction->getValue()) {
            case FlowDirection::ROW:
                return $rowIndex * count($this->coordinateSystem->columnCoordinates()) + $columnIndex + 1;
            case FlowDirection::COLUMN:
                return $columnIndex * count($this->coordinateSystem->rowCoordinates()) + $rowIndex + 1;
            // @codeCoverageIgnoreStart all Enums are listed and this should never happen
            default:
                throw new UnexpectedValueException('Unexpected flow direction value:' . $direction->getValue());
            // @codeCoverageIgnoreEnd
        }
    }

    private static function assertPositionInRange(CoordinateSystem $coordinateSystem, int $position): void
    {
        $maxPosition = count($coordinateSystem->columnCoordinates()) * count($coordinateSystem->rowCoordinates());
        if (! in_array($position, range(self::MIN_POSITION, $maxPosition), true)) {
            throw new InvalidArgumentException("Expected a position between 1-{$maxPosition}, got: {$position}.");
        }
    }
}

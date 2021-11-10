<?php

declare(strict_types=1);

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
        $this->coordinateSystem = $coordinateSystem;

        if (! in_array($row, $coordinateSystem->rowCoordinates(), true)) {
            throw new InvalidArgumentException("Expected a row with value of {$this->rowList()}, got {$row}.");
        }
        $this->row = $row;

        if (! in_array($column, $this->coordinateSystem->columnCoordinates(), true)) {
            throw new InvalidArgumentException("Expected a column with value of {$this->columnList()}, got {$column}.");
        }
        $this->column = $column;
    }

    public static function fromString(string $coordinateString, CoordinateSystem96Well $coordinateSystem): self
    {
        $valid = \Safe\preg_match(
            '/^('.implode('|', $coordinateSystem->rowCoordinates()).')('.implode('|', $coordinateSystem->columnCoordinates()).')$/',
            $coordinateString,
            $matches
        );

        if (0 === $valid) {
            throw new InvalidArgumentException("Expected a coordinate such as A1 or F11, got: {$coordinateString}.");
        }

        return new self($matches[1], (int) $matches[2], $coordinateSystem);
    }

    public function __toString()
    {
        return $this->row.$this->column;
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
            default:
                throw new UnexpectedValueException('Unexpected flow direction value:'.$direction->getValue());
        }
    }

    public function position(FlowDirection $direction): int
    {
        $rowIndex = array_search($this->row, $this->coordinateSystem->rowCoordinates(), true);
        $columnIndex = array_search($this->column, $this->coordinateSystem->columnCoordinates(), true);

        if (! is_int($rowIndex) || ! is_int($columnIndex)) {
            throw new UnexpectedValueException('rowIndex and columnIndex need to be integers: values are:'.$rowIndex.' '.$columnIndex);
        }

        switch ($direction->getValue()) {
            case FlowDirection::ROW()->getValue():
                return $rowIndex * count($this->coordinateSystem->columnCoordinates()) + $columnIndex + 1;
            case FlowDirection::COLUMN()->getValue():
                return $columnIndex * count($this->coordinateSystem->rowCoordinates()) + $rowIndex + 1;
            default:
                throw new UnexpectedValueException('Unexpected flow direction value:'.$direction->getValue());
        }
    }

    private static function assertPositionInRange(CoordinateSystem $coordinateSystem, int $position): void
    {
        $maxPosition = count($coordinateSystem->columnCoordinates()) * count($coordinateSystem->rowCoordinates());
        if (! in_array($position, range(self::MIN_POSITION, $maxPosition), true)) {
            throw new InvalidArgumentException("Expected a position between 1-{$maxPosition}, got: {$position}.");
        }
    }

    private function rowList(): string
    {
        return implode(',', $this->coordinateSystem->rowCoordinates());
    }

    private function columnList(): string
    {
        return implode(',', $this->coordinateSystem->columnCoordinates());
    }
}

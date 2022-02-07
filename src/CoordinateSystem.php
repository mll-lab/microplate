<?php declare(strict_types=1);

namespace Mll\Microplate;

abstract class CoordinateSystem
{
    public const PAD_STRING_ZERO = '0';

    /**
     * @return list<string>
     */
    abstract public function rows(): array;

    /**
     * @return list<int>
     */
    abstract public function columns(): array;

    /**
     * @return list<string>
     */
    public function paddedColumns(): array
    {
        $paddedColumns = [];
        foreach ($this->columns() as $column) {
            $paddedColumns[] = str_pad((string) $column, strlen((string) $this->columnsCount()), self::PAD_STRING_ZERO, STR_PAD_LEFT);
        }

        return $paddedColumns;
    }

    public function rowForRowFlowPosition(int $position): string
    {
        return $this->rows()[floor(($position - 1) / $this->columnsCount())];
    }

    public function rowForColumnFlowPosition(int $position): string
    {
        return $this->rows()[($position - 1) % $this->rowsCount()];
    }

    public function columnForRowFlowPosition(int $position): int
    {
        return $this->columns()[($position - 1) % $this->columnsCount()];
    }

    public function columnForColumnFlowPosition(int $position): int
    {
        return $this->columns()[floor(($position - 1) / $this->rowsCount())];
    }

    public function positionsCount(): int
    {
        return $this->columnsCount() * $this->rowsCount();
    }

    /**
     * @return iterable<int, Coordinate>
     */
    public function all(): iterable
    {
        foreach ($this->columns() as $column) {
            foreach ($this->rows() as $row) {
                yield new Coordinate($row, $column, $this);
            }
        }
    }

    public function rowsCount(): int
    {
        return count($this->rows());
    }

    public function columnsCount(): int
    {
        return count($this->columns());
    }
}

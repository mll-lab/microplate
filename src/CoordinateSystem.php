<?php declare(strict_types=1);

namespace Mll\Microplate;

abstract class CoordinateSystem
{
    /**
     * @return list<string>
     */
    abstract public function rows(): array;

    /**
     * @return list<int>
     */
    abstract public function columns(): array;

    /**
     * List of columns, 0-padded to all have the same length.
     *
     * @return list<string>
     */
    public function paddedColumns(): array
    {
        $paddedColumns = [];
        foreach ($this->columns() as $column) {
            $paddedColumns[] = $this->padColumn($column);
        }

        return $paddedColumns;
    }

    /**
     * 0-pad column to be as long as the longest column in the coordinate system.
     */
    public function padColumn(int $column): string
    {
        $maxColumnLength = strlen((string) $this->columnsCount());

        return str_pad((string) $column, $maxColumnLength, '0', STR_PAD_LEFT);
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
     * Returns all possible coordinates of the system, ordered by column then row.
     *
     * e.g. A1, A2, B1, B2
     *
     * @return iterable<int, Coordinate<$this>>
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

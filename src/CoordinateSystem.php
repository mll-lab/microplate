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

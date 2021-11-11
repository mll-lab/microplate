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
        return $this->rows()[floor(($position - 1) / count($this->columns()))];
    }

    public function rowForColumnFlowPosition(int $position): string
    {
        return $this->rows()[($position - 1) % count($this->rows())];
    }

    public function columnForRowFlowPosition(int $position): int
    {
        return $this->columns()[($position - 1) % count($this->columns())];
    }

    public function columnForColumnFlowPosition(int $position): int
    {
        return $this->columns()[floor(($position - 1) / count($this->rows()))];
    }
}

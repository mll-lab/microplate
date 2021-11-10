<?php

declare(strict_types=1);

namespace Mll\Microplate;

abstract class CoordinateSystem implements \Stringable
{
    /**
     * @return list<string>
     */
    abstract public function rowCoordinates(): array;

    /**
     * @return list<int>
     */
    abstract public function columnCoordinates(): array;

    public function rowForRowFlowPosition(int $position): string
    {
        return $this->rowCoordinates()[floor(($position - 1) / count($this->columnCoordinates()))];
    }

    public function rowForColumnFlowPosition(int $position): string
    {
        return $this->rowCoordinates()[($position - 1) % count($this->rowCoordinates())];
    }

    public function columnForRowFlowPosition(int $position): int
    {
        return $this->columnCoordinates()[($position - 1) % count($this->columnCoordinates())];
    }

    public function columnForColumnFlowPosition(int $position): int
    {
        return $this->columnCoordinates()[floor(($position - 1) / count($this->rowCoordinates()))];
    }

    public function __toString()
    {
        return get_class($this);
    }
}

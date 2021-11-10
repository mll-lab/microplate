<?php

namespace Mll\Microplate;

class CoordinateSystem12Well extends CoordinateSystem
{
    public const COORDINATES_ROWS = ['A', 'B', 'C'];
    public const COORDINATES_COLUMNS = [1, 2, 3, 4];

    public function rowCoordinates(): array
    {
        return self::COORDINATES_ROWS;
    }

    public function columnCoordinates(): array
    {
        return self::COORDINATES_COLUMNS;
    }
}
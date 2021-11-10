<?php

namespace Mll\Microplate;

class CoordinateSystem96Well extends CoordinateSystem
{
    public const COORDINATES_ROWS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    public const COORDINATES_COLUMNS = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

    public function rowCoordinates(): array
    {
        return self::COORDINATES_ROWS;
    }

    public function columnCoordinates(): array
    {
        return self::COORDINATES_COLUMNS;
    }
}
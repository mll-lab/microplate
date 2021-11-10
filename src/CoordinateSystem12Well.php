<?php

declare(strict_types=1);

namespace Mll\Microplate;

class CoordinateSystem12Well extends CoordinateSystem
{
    public function rowCoordinates(): array
    {
        return range('A', 'C');
    }

    public function columnCoordinates(): array
    {
        return range(1, 4);
    }
}

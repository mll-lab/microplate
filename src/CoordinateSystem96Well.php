<?php declare(strict_types=1);

namespace Mll\Microplate;

class CoordinateSystem96Well extends CoordinateSystem
{
    public function rowCoordinates(): array
    {
        return range('A', 'H');
    }

    public function columnCoordinates(): array
    {
        return range(1, 12);
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate;

class CoordinateSystem12Well extends CoordinateSystem
{
    public function rows(): array
    {
        return range('A', 'C');
    }

    public function columns(): array
    {
        return range(1, 4);
    }
}

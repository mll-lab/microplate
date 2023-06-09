<?php declare(strict_types=1);

namespace Mll\Microplate;

final class CoordinateSystem48Well extends CoordinateSystem
{
    public function rows(): array
    {
        return range('A', 'F');
    }

    public function columns(): array
    {
        return range(1, 8);
    }
}

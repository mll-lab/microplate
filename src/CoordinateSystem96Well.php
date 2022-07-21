<?php declare(strict_types=1);

namespace Mll\Microplate;

final class CoordinateSystem96Well extends CoordinateSystem
{
    public function rows(): array
    {
        return range('A', 'H');
    }

    public function columns(): array
    {
        return range(1, 12);
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate\MicroplateSet;

use Mll\Microplate\CoordinateSystem;

/**
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-extends MicroplateSet<TCoordinateSystem>
 */
final class MicroplateSetABCD extends MicroplateSet
{
    /** Duplicates @see MicroplateSet::plateCount() for static contexts. */
    public const PLATE_COUNT = 4;

    public function plateIDs(): array
    {
        return ['A', 'B', 'C', 'D'];
    }
}

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
    /**
     * {@inheritDoc}
     */
    public function plateIDs(): array
    {
        return ['A', 'B', 'C', 'D'];
    }
}

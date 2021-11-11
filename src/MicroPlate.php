<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 */
class MicroPlate
{
    /**
     * @var TCoordinateSystem
     */
    public CoordinateSystem $coordinateSystem;

    /**
     * @var Collection<array{Coordinate<TCoordinateSystem>, TWell}>
     */
    private Collection $wells;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->wells = new Collection([]);
        $this->coordinateSystem = $coordinateSystem;
    }

    /**
     * TODO how to deal with duplicates?
     *
     * @param Coordinate<TCoordinateSystem> $coordinate
     * @param TWell $content
     */
    public function addWell(Coordinate $coordinate, $content): void
    {
        $this->wells->add([$coordinate, $content]);
    }

    public function getWells(): Collection
    {
        return $this->wells;
    }
}

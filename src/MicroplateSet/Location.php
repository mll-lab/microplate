<?php declare(strict_types=1);

namespace Mll\Microplate\MicroplateSet;

use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem;

/**
 * @template TCoordinateSystem of CoordinateSystem
 */
final class Location
{
    public string $plateID;

    /**
     * @var \Mll\Microplate\Coordinate<TCoordinateSystem>
     */
    public Coordinate $coordinate;

    /**
     * @param \Mll\Microplate\Coordinate<TCoordinateSystem> $coordinate
     */
    public function __construct(Coordinate $coordinate, string $plateID)
    {
        $this->coordinate = $coordinate;
        $this->plateID = $plateID;
    }
}

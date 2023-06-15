<?php declare(strict_types=1);

namespace Mll\Microplate\MicroplateSet;

use Mll\Microplate\Coordinates;
use Mll\Microplate\CoordinateSystem;

/**
 * @template TCoordinateSystem of CoordinateSystem
 */
final class Location
{
    public string $plateID;

    /** @var \Mll\Microplate\Coordinates<TCoordinateSystem> */
    public Coordinates $coordinates;

    /** @param \Mll\Microplate\Coordinates<TCoordinateSystem> $coordinates */
    public function __construct(Coordinates $coordinates, string $plateID)
    {
        $this->coordinates = $coordinates;
        $this->plateID = $plateID;
    }
}

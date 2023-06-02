<?php declare(strict_types=1);

namespace Mll\Microplate\MicroplateSet;

use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem;
use Mll\Microplate\Enums\FlowDirection;

/**
 *  @template TCoordinateSystem of CoordinateSystem
 */
abstract class MicroplateSet
{
    /**
     * @var TCoordinateSystem
     */
    public CoordinateSystem $coordinateSystem;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->coordinateSystem = $coordinateSystem;
    }

    /**
     * @return list<string>
     */
    abstract public function plateIDs(): array;

    public function plateCount(): int
    {
        return count($this->plateIDs());
    }

    public function positionsCount(): int
    {
        return $this->coordinateSystem->positionsCount() * $this->plateCount();
    }

    /**
     * @return Location<TCoordinateSystem>
     */
    public function locationFromPosition(int $setPosition, FlowDirection $direction): Location
    {
        $positionsCount = $this->positionsCount();
        if ($setPosition > $positionsCount || $setPosition < Coordinate::MIN_POSITION) {
            throw new \OutOfRangeException("Expected a position between 1-{$positionsCount}, got: {$setPosition}.");
        }

        $plateIndex = (int) floor(($setPosition - 1) / $this->coordinateSystem->positionsCount());
        $positionOnSinglePlate = $setPosition - ($plateIndex * $this->coordinateSystem->positionsCount());

        return new Location(
            Coordinate::fromPosition($positionOnSinglePlate, $direction, $this->coordinateSystem),
            $this->plateIDs()[$plateIndex]
        );
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\WellNotEmptyException;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-extends AbstractMicroplate<TWell, TCoordinateSystem>
 *
 * @phpstan-type WellsCollection Collection<string, TWell|null>
 */
final class Microplate extends AbstractMicroplate
{
    /**
     * @var WellsCollection
     */
    protected Collection $wells;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        parent::__construct($coordinateSystem);

        $this->clearWells();
    }

    /**
     * @return WellsCollection
     */
    public function wells(): Collection
    {
        return $this->wells;
    }

    /**
     * @param Coordinate<TCoordinateSystem> $coordinate
     */
    public static function position(Coordinate $coordinate, FlowDirection $direction): int
    {
        return $coordinate->position($direction);
    }

    /**
     * @param TWell $content
     * @param Coordinate<TCoordinateSystem> $coordinate
     *
     * @throws WellNotEmptyException
     */
    public function addWell(Coordinate $coordinate, $content): void
    {
        $this->assertIsWellEmpty($coordinate, $content);
        $this->setWell($coordinate, $content);
    }

    /**
     * Set the well at the given coordinate to the given content.
     *
     * @param Coordinate<TCoordinateSystem> $coordinate
     * @param TWell $content
     */
    public function setWell(Coordinate $coordinate, $content): void
    {
        $this->wells[$coordinate->toString()] = $content;
    }

    /**
     * @param Coordinate<TCoordinateSystem> $coordinate
     * @param TWell $content
     *
     * @throws WellNotEmptyException
     */
    private function assertIsWellEmpty(Coordinate $coordinate, $content): void
    {
        if (! $this->isWellEmpty($coordinate)) {
            throw new WellNotEmptyException(
                'Well with coordinate "' . $coordinate->toString() . '" is not empty. Use setWell() to overwrite the coordinate. Well content "' . serialize($content) . '" was not added.'
            );
        }
    }

    /**
     * Clearing the wells will reinitialize all well position of the coordinate system.
     */
    public function clearWells(): void
    {
        /**
         * Flow direction is irrelevant during initialization, it is not a property of
         * a plate but rather a property of the access to the plate.
         */

        /** @var array<string, TWell|null> $wells */
        $wells = [];
        foreach ($this->coordinateSystem->all() as $coordinate) {
            $wells[$coordinate->toString()] = self::EMPTY_WELL;
        }

        $this->wells = new Collection($wells);
    }

    /**
     * @param TWell $content
     *
     * @throws MicroplateIsFullException
     *
     * @return Coordinate<TCoordinateSystem>
     */
    public function addToNextFreeWell($content, FlowDirection $flowDirection): Coordinate
    {
        $coordinate = $this->nextFreeWellCoordinate($flowDirection);
        $this->wells[$coordinate->toString()] = $content;

        return $coordinate;
    }

    /**
     * @throws MicroplateIsFullException
     *
     * @return Coordinate<TCoordinateSystem>
     */
    public function nextFreeWellCoordinate(FlowDirection $flowDirection): Coordinate
    {
        $coordinateString = $this->sortedWells($flowDirection)
            ->search(self::EMPTY_WELL);

        if (! is_string($coordinateString)) {
            throw new MicroplateIsFullException();
        }

        return Coordinate::fromString($coordinateString, $this->coordinateSystem);
    }
}

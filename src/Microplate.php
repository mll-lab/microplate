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
    /** @var WellsCollection */
    protected Collection $wells;

    /** @param TCoordinateSystem $coordinateSystem */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        parent::__construct($coordinateSystem);

        $this->clearWells();
    }

    /** @return WellsCollection */
    public function wells(): Collection
    {
        return $this->wells;
    }

    /** @param Coordinates<TCoordinateSystem> $coordinate */
    public static function position(Coordinates $coordinate, FlowDirection $direction): int
    {
        return $coordinate->position($direction);
    }

    /**
     * @param TWell $content
     * @param Coordinates<TCoordinateSystem> $coordinate
     *
     * @throws WellNotEmptyException
     */
    public function addWell(Coordinates $coordinate, $content): void
    {
        $this->assertIsWellEmpty($coordinate, $content);
        $this->setWell($coordinate, $content);
    }

    /**
     * Set the well at the given coordinates to the given content.
     *
     * @param Coordinates<TCoordinateSystem> $coordinates
     * @param TWell $content
     */
    public function setWell(Coordinates $coordinates, $content): void
    {
        $this->wells[$coordinates->toString()] = $content;
    }

    /**
     * @param Coordinates<TCoordinateSystem> $coordinate
     * @param TWell $content
     *
     * @throws WellNotEmptyException
     */
    private function assertIsWellEmpty(Coordinates $coordinate, $content): void
    {
        if (! $this->isWellEmpty($coordinate)) {
            throw new WellNotEmptyException(
                'Well with coordinates "' . $coordinate->toString() . '" is not empty. Use setWell() to overwrite the coordinate. Well content "' . serialize($content) . '" was not added.'
            );
        }
    }

    /** Clearing the wells will reinitialize all well position of the coordinate system. */
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
     *@throws MicroplateIsFullException
     *
     * @return Coordinates<TCoordinateSystem>
     */
    public function addToNextFreeWell($content, FlowDirection $flowDirection): Coordinates
    {
        $coordinates = $this->nextFreeWellCoordinates($flowDirection);
        $this->wells[$coordinates->toString()] = $content;

        return $coordinates;
    }

    /**
     *@throws MicroplateIsFullException
     *
     * @return Coordinates<TCoordinateSystem>
     */
    public function nextFreeWellCoordinates(FlowDirection $flowDirection): Coordinates
    {
        $coordinateString = $this->sortedWells($flowDirection)
            ->search(self::EMPTY_WELL);

        if (! is_string($coordinateString)) {
            throw new MicroplateIsFullException();
        }

        return Coordinates::fromString($coordinateString, $this->coordinateSystem);
    }
}

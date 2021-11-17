<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\UnexpectedFlowDirection;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-type WellsCollection Collection<string, TWell|null>
 */
abstract class AbstractMicroplate
{
    public const EMPTY_WELL = null;

    /**
     * @var TCoordinateSystem
     */
    public CoordinateSystem $coordinateSystem;

    /**
     * @var WellsCollection
     */
    protected Collection $wells;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->coordinateSystem = $coordinateSystem;

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
     *
     * @return TWell|null
     */
    public function well(Coordinate $coordinate)
    {
        return $this->wells()[$coordinate->toString()];
    }

    /**
     * @param Coordinate<TCoordinateSystem> $coordinate
     */
    public function isWellEmpty(Coordinate $coordinate): bool
    {
        return self::EMPTY_WELL === $this->well($coordinate);
    }

    /**
     * @return WellsCollection
     */
    public function sortedWells(FlowDirection $flowDirection): Collection
    {
        return $this->wells()->sortBy(
            /**
             * @param TWell $value
             */
            function ($value, string $key) use ($flowDirection): string {
                switch ($flowDirection->getValue()) {
                    case FlowDirection::ROW:
                        return $key;
                    case FlowDirection::COLUMN:
                        $coordinate = Coordinate::fromString($key, $this->coordinateSystem);

                        return $coordinate->column . $coordinate->row;
                    // @codeCoverageIgnoreStart all Enums are listed and this should never happen
                    default:
                        throw new UnexpectedFlowDirection($flowDirection);
                    // @codeCoverageIgnoreEnd
                }
            },
            SORT_NATURAL
        );
    }

    /**
     * @return Collection<string, null>
     */
    public function freeWells(): Collection
    {
        return $this->wells->filter(
            /**
             * @param TWell $value
             */
            static fn ($value): bool => self::EMPTY_WELL === $value
        );
    }

    /**
     * @return Collection<string, TWell>
     */
    public function filledWells(): Collection
    {
        return $this->wells()->filter(
            /**
             * @param TWell $value
             */
            static fn ($value): bool => self::EMPTY_WELL !== $value
        );
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
        $this->wells = new Collection();
        foreach ($this->coordinateSystem->columns() as $column) {
            foreach ($this->coordinateSystem->rows() as $row) {
                $this->wells[$row . $column] = self::EMPTY_WELL;
            }
        }
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
}

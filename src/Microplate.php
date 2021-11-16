<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\UnexpectedFlowDirection;
use Mll\Microplate\Exceptions\WellNotEmptyException;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-type WellsCollection Collection<string, TWell|null>
 */
class Microplate
{
    public const EMPTY_WELL = null;

    /**
     * @var TCoordinateSystem
     */
    public CoordinateSystem $coordinateSystem;

    /**
     * @var WellsCollection
     */
    public Collection $wells;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->coordinateSystem = $coordinateSystem;

        $this->clearWells();
    }

    /**
     * @param Coordinate<TCoordinateSystem> $coordinate
     */
    public function position(Coordinate $coordinate, FlowDirection $direction): int
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
     * @param Coordinate<TCoordinateSystem> $coordinate
     *
     * @return mixed the content of the well
     */
    public function well(Coordinate $coordinate)
    {
        return $this->wells[$coordinate->toString()];
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
        $this->setWell($coordinate, $content);

        return $coordinate;
    }

    /**
     * Clearing the wells will reinitialize all well position of the coordinate system.
     */
    public function clearWells(): void
    {
        /**
         * The flow direction is ignored during initialization because the plate intentionally has no
         * flow-direction property.
         * The flow direction is a property of the access to the plate.
         */
        $this->wells = new Collection([]);
        foreach ($this->coordinateSystem->columns() as $column) {
            foreach ($this->coordinateSystem->rows() as $row) {
                $this->wells[$row . $column] = self::EMPTY_WELL;
            }
        }
    }

    /**
     * @param Coordinate<TCoordinateSystem> $coordinate
     */
    public function isWellEmpty(Coordinate $coordinate): bool
    {
        return self::EMPTY_WELL === $this->well($coordinate);
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
     * @return WellsCollection
     */
    public function sortedWells(FlowDirection $flowDirection): Collection
    {
        return $this->wells->sortBy(
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
        return $this->wells->filter(
            /**
             * @param TWell $value
             */
            static fn ($value): bool => self::EMPTY_WELL !== $value
        );
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
            throw new MicroplateIsFullException('No free spots left on plate');
        }

        return Coordinate::fromString($coordinateString, $this->coordinateSystem);
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
}

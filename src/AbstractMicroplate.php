<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Enums\FlowDirection;
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
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->coordinateSystem = $coordinateSystem;
    }

    /**
     * @return WellsCollection
     */
    abstract public function wells(): Collection;

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
             * @param TWell $content
             */
            function ($content, string $key) use ($flowDirection): string {
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
        return $this->wells()->filter(
            /**
             * @param TWell $content
             */
            static fn ($content): bool => self::EMPTY_WELL === $content
        );
    }

    /**
     * @return Collection<string, TWell>
     */
    public function filledWells(): Collection
    {
        return $this->wells()->filter(
            /**
             * @param TWell $content
             */
            static fn ($content): bool => self::EMPTY_WELL !== $content
        );
    }

    /**
     * @return callable(TWell|null $content, string $coordinateString): bool
     */
    public function matchRow(string $row): callable
    {
        return function ($content, string $coordinateString) use ($row): bool {
            $coordinate = Coordinate::fromString($coordinateString, $this->coordinateSystem);

            return $coordinate->row === $row;
        };
    }

    /**
     * @return callable(TWell|null $content, string $coordinateString): bool
     */
    public function matchColumn(int $column): callable
    {
        return function ($content, string $coordinateString) use ($column): bool {
            $coordinate = Coordinate::fromString($coordinateString, $this->coordinateSystem);

            return $coordinate->column === $column;
        };
    }

    /**
     * @return callable(TWell $content, string $coordinateString): WellWithCoordinate<TWell, TCoordinateSystem>
     */
    public function toWellWithCoordinateMapper(): callable
    {
        return fn ($content, string $coordinateString): WellWithCoordinate => new WellWithCoordinate(
            $content,
            Coordinate::fromString($coordinateString, $this->coordinateSystem)
        );
    }

    /**
     * Are all filled wells placed in a single connected block without gaps between them?
     *
     * Returns `false` if all wells are empty.
     */
    public function isConsecutive(FlowDirection $flowDirection): bool
    {
        $positions = $this->filledWells()
            /**
             * @param TWell $content
             */
            ->map(
                fn ($content, string $coordinateString): int => Coordinate::fromString($coordinateString, $this->coordinateSystem)->position($flowDirection)
            );

        return ($positions->max() - $positions->min() + 1) === $positions->count();
    }
}

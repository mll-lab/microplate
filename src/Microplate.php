<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\WellNotEmptyException;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-type WellsCollection Collection<string, TWell|null>
 */
class Microplate extends AbstractMicroplate
{
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
}

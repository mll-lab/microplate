<?php declare(strict_types=1);

namespace Mll\Microplate;

/**
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 */
final class WellWithCoordinate
{
    /**
     * @var TWell
     */
    public $content;

    /**
     * @var Coordinate<TCoordinateSystem>
     */
    public Coordinate $coordinate;

    /**
     * @param TWell $content
     * @param Coordinate<TCoordinateSystem> $coordinate
     */
    public function __construct($content, Coordinate $coordinate)
    {
        $this->content = $content;
        $this->coordinate = $coordinate;
    }
}

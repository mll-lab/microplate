<?php

declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;
use Mll\Microplate\Exceptions\IncompatibleCoordinateSystemException;

class MicroPlate
{
    private CoordinateSystem $coordinateSystem;

    /**
     * @var Collection<array<int, mixed>>
     */
    private Collection $wells;

    public function __construct(CoordinateSystem $coordinateSystem)
    {
        $this->wells = collect([]);
        $this->coordinateSystem = $coordinateSystem;
    }

    /**
     * @phpstan-param mixed $content
     *
     * @throws IncompatibleCoordinateSystemException
     */
    public function addWell(Coordinate $coodinate, $content): void
    {
        if (get_class($coodinate->coordinateSystem) !== get_class($this->coordinateSystem)) {
            throw new IncompatibleCoordinateSystemException('Can not add a content to a well with CoordinateSystem "' . $coodinate->coordinateSystem . '" to the plate with CoordinateSystem "' . $this->coordinateSystem . '"');
        }
        $this->wells->add([$coodinate, $content]);
    }

    /**
     * @return Collection<array<int, mixed>>
     */
    public function getWells(): Collection
    {
        return $this->wells;
    }
}

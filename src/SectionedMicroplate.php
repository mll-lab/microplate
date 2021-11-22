<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;

/**
 * A SectionedMicroplate is a microplate with sections of samples on it.
 *
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 *
 * @phpstan-extends AbstractMicroplate<TWell, TCoordinateSystem>
 */
class SectionedMicroplate extends AbstractMicroplate
{
    /**
     * @var Collection<string, Section>
     */
    public Collection $sections;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        parent::__construct($coordinateSystem);
    }

    /**
     * @param class-string<Section> $sectionClass
     */
    public function addSection(string $sectionClass): Section
    {
        return $this->sections[] = new $sectionClass($this);
    }

    public function removeSection(Section $section): void
    {
        foreach ($this->sections as $i => $s) {
            if ($s === $section) {
                unset($this->sections[$i]);
            }
        }
    }

    public function wells(): Collection
    {
        parent::clearWells();

        $this->sections->map(function (Section $section): void {
            $section->sectionItems->map(function ($sectionItem) {
                /** @var string $coordinateWithEmptyWell checked in the process of adding a well to the section */
                $coordinateWithEmptyWell = $this->wells->search(self::EMPTY_WELL);
                $this->wells[$coordinateWithEmptyWell] = $sectionItem;
            });
        });

        return $this->wells;
    }

    public function clearWells(): void
    {
        $this->sections = new Collection();
        parent::clearWells();
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;

/**
 * A SectionedMicroplate is a microplate with sections of samples on it.
 *
 * @template TWell
 * @template TCoordinateSystem of CoordinateSystem
 * @template TSection of AbstractSection
 *
 * @phpstan-extends AbstractMicroplate<TWell, TCoordinateSystem>
 */
class SectionedMicroplate extends AbstractMicroplate
{
    /**
     * @var Collection<string, TSection>
     */
    public Collection $sections;

    /**
     * @param TCoordinateSystem $coordinateSystem
     */
    public function __construct(CoordinateSystem $coordinateSystem)
    {
        parent::__construct($coordinateSystem);

        $this->clearSections();
    }

    /**
     * @param class-string<TSection> $sectionClass
     */
    public function addSection(string $sectionClass): AbstractSection
    {
        return $this->sections[] = new $sectionClass($this);
    }

    /**
     * @param TSection $section
     */
    public function removeSection(AbstractSection $section): void
    {
        foreach ($this->sections as $i => $s) {
            if ($s === $section) {
                unset($this->sections[$i]);
            }
        }
    }

    public function wells(): Collection
    {
        /**
         * @var Collection<array{TWell|null, Coordinate}>
         */
        $zipped = $this->sections
            ->map(fn (AbstractSection $section) => $section->sectionItems)
            ->flatten(1)
            ->values()
            ->zip($this->coordinateSystem->all())
            ->map(fn (Collection $mapping) => $mapping->all());

        return $zipped->mapWithKeys(function (array $mapping): array {
            [$sectionItem, $coordinate] = $mapping;

            return [$coordinate->toString() => $sectionItem];
        });
    }

    public function clearSections(): void
    {
        $this->sections = new Collection();
    }
}

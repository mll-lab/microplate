<?php declare(strict_types=1);

namespace Mll\Microplate;

use Illuminate\Support\Collection;

/**
 * @template TSectionWell
 * @template TSectionedMicroplate of SectionedMicroplate
 */
abstract class Section
{
    public Collection $sectionItems;

    public SectionedMicroplate $sectionedMicroplate;

    /**
     * @param TSectionedMicroplate $sectionedMicroplate
     */
    public function __construct(SectionedMicroplate $sectionedMicroplate)
    {
        $this->sectionedMicroplate = $sectionedMicroplate;
        $this->sectionItems = new Collection();
    }

    public function occupiedWellsCount(): int
    {
        return $this->sectionItems->count();
    }

    /**
     * @param TSectionWell $content
     */
    abstract public function addWell($content): void;
}

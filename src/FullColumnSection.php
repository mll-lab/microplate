<?php declare(strict_types=1);

namespace Mll\Microplate;

use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\SectionIsFullException;
use function count;

/**
 * A Section that occupies all wells of a column if one sample exists in this column. Samples of other sections are
 * not allowed in this occupied wells. Occupied wells can still be filled with samples of the same type.
 *
 * @template TSectionWell
 */
class FullColumnSection extends Section
{
    public const RESERVED_WELL = 'reserved';

    /**
     * @param TSectionWell $content
     */
    public function addWell($content): void
    {
        if (0 === $this->sectionedMicroplate->wells()->filter(
            static fn ($value): bool => self::RESERVED_WELL === $value || Microplate::EMPTY_WELL === $value
        )->count()) {
            throw new MicroplateIsFullException();
        }

        if (false !== $this->nextReservedWell()) {
            $this->sectionItems[$this->nextReservedWell()] = $content;

            return;
        }

        if ($this->sectionCanGrow()) {
            $this->initializeNewColumnForSection();
            $this->sectionItems[$this->nextReservedWell()] = $content;

            return;
        }
        throw new SectionIsFullException();
    }

    private function initializeNewColumnForSection(): void
    {
        foreach ($this->sectionedMicroplate->coordinateSystem->rows() as $row) {
            $this->sectionItems->push(self::RESERVED_WELL);
        }
    }

    /**
     * @return false|int
     */
    private function nextReservedWell()
    {
        return $this->sectionItems->search(self::RESERVED_WELL);
    }

    private function sectionCanGrow(): bool
    {
        $coordinateSystem = $this->sectionedMicroplate->coordinateSystem;
        return $this->sectionedMicroplate->freeWells()->count() >= count($coordinateSystem->rows());
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate;

use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\SectionIsFullException;

/**
 * A Section that occupies all wells of a column if one sample exists in this column. Samples of other sections are
 * not allowed in this occupied wells. Occupied wells can still be filled with samples of the same type.
 *
 * @template TSectionWell
 */
class FullColumnSection extends AbstractSection
{
    public function __construct(SectionedMicroplate $sectionedMicroplate)
    {
        parent::__construct($sectionedMicroplate);
        $this->growSection();
    }

    /**
     * @param TSectionWell $content
     *
     * @throws MicroplateIsFullException
     * @throws SectionIsFullException
     */
    public function addWell($content): void
    {
        if ($this->sectionedMicroplate->freeWells()->isEmpty()) {
            throw new MicroplateIsFullException();
        }

        $nextReservedWell = $this->nextReservedWell();
        if (false !== $nextReservedWell) {
            $this->sectionItems[$nextReservedWell] = $content;

            return;
        }

        $this->growSection();

        /** @var int $nextReservedWell Guaranteed to be found after we grew the section */
        $nextReservedWell = $this->nextReservedWell();
        $this->sectionItems[$nextReservedWell] = $content;
    }

    /**
     * Grows the section by initializing a new column with empty wells.
     *
     * @throws SectionIsFullException
     */
    private function growSection(): void
    {
        if (! $this->sectionCanGrow()) {
            throw new SectionIsFullException();
        }

        foreach ($this->sectionedMicroplate->coordinateSystem->rows() as $row) {
            $this->sectionItems->push(AbstractMicroplate::EMPTY_WELL);
        }
    }

    /**
     * @return false|int
     */
    private function nextReservedWell()
    {
        return $this->sectionItems->search(AbstractMicroplate::EMPTY_WELL);
    }

    private function sectionCanGrow(): bool
    {
        $totalReservedColumns = $this->sectionedMicroplate->sections->sum(fn (self $section) => $section->reservedColumns());
        $availableColumns = $this->sectionedMicroplate->coordinateSystem->columnsCount();

        return $totalReservedColumns < $availableColumns;
    }

    private function reservedColumns(): int
    {
        return (int) ceil($this->sectionItems->count() / $this->sectionedMicroplate->coordinateSystem->rowsCount());
    }
}

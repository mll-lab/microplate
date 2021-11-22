<?php declare(strict_types=1);

namespace Mll\Microplate;

use Mll\Microplate\Exceptions\MicroplateIsFullException;

/**
 * @template TSectionWell
 */
class Section extends AbstractSection
{
    /**
     * @param TSectionWell $content
     */
    public function addWell($content): void
    {
        if ($this->sectionedMicroplate->freeWells()->isEmpty()) {
            throw new MicroplateIsFullException();
        }

        $this->sectionItems->push($content);
    }
}

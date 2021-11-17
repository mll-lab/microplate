<?php declare(strict_types=1);

namespace Mll\Microplate;

use Mll\Microplate\Exceptions\MicroplateIsFullException;

/**
 * @template TSectionWell
 * @template TSectionedMicroplate of SectionedMicroplate
 */
class DefaultSection extends Section
{
    /**
     * @param TSectionWell $content
     */
    public function addWell($content): void
    {
        if (0 === $this->sectionedMicroplate->freeWells()->count()) {
            throw new MicroplateIsFullException();
        }
        $this->sectionItems->push($content);
    }
}

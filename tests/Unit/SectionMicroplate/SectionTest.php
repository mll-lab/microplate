<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit\SectionedMicroplate;

use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Section;
use Mll\Microplate\SectionedMicroplate;
use PHPUnit\Framework;

class SectionTest extends Framework\TestCase
{
    public function testSectionThrowsWhenFull(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $section = $sectionedMicroplate->addSection(Section::class);
        self::assertCount(1, $sectionedMicroplate->sections);
        self::assertCount(96, $sectionedMicroplate->freeWells());

        foreach ($coordinateSystem->all() as $i => $coordinate) {
            $section->addWell('column' . $i);
            self::assertCount($i + 1, $sectionedMicroplate->filledWells());
        }

        self::assertCount(0, $sectionedMicroplate->freeWells());
        $this->expectExceptionObject(new MicroplateIsFullException());

        $section->addWell('foo');
    }
}

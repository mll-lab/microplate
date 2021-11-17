<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Exceptions\SectionIsFullException;
use Mll\Microplate\FullColumnSection;
use Mll\Microplate\SectionedMicroplate;
use PHPUnit\Framework;

class SectionedMicroplateTest extends Framework\TestCase
{
    public function testCanAddSectionsAndWellsToSection(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $section1 = $sectionedMicroplate->addSection('Mll\Microplate\DefaultSection');
        self::assertCount(1, $sectionedMicroplate->sections);

        $section2 = $sectionedMicroplate->addSection('Mll\Microplate\DefaultSection');
        self::assertCount(2, $sectionedMicroplate->sections);

        self::assertCount(0, $sectionedMicroplate->filledWells());
        self::assertCount(96, $sectionedMicroplate->freeWells());

        $content1 = 'content1';
        $section1->addWell($content1);
        $content2 = 'content2';
        $content3 = 'content3';
        $section2->addWell($content2);
        $section2->addWell($content3);

        self::assertCount(3, $sectionedMicroplate->filledWells());
        self::assertCount(93, $sectionedMicroplate->freeWells());

        self::assertSame(1, $section1->occupiedWellsCount());
        self::assertSame($content1, $section1->sectionItems->first());

        self::assertSame(2, $section2->occupiedWellsCount());
        self::assertSame($content2, $section2->sectionItems->first());
        self::assertSame($content3, $section2->sectionItems->last());
    }

    public function testFullColumnSectionThrowsWhenFull(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $section = $sectionedMicroplate->addSection('Mll\Microplate\FullColumnSection');
        self::assertCount(1, $sectionedMicroplate->sections);
        self::assertCount(96, $sectionedMicroplate->freeWells());

        foreach (array_keys($coordinateSystem->columns()) as $i) {
            foreach ($coordinateSystem->rows() as $ignored1) {
                $section->addWell('column' . $i);
                self::assertCount(8 * ($i + 1), $sectionedMicroplate->filledWells());
            }
        }

        self::assertCount(0, $sectionedMicroplate->freeWells());
        $this->expectExceptionObject(new MicroplateIsFullException());

        $section->addWell('foo');
    }

    public function testFullColumnSection(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $section1 = $sectionedMicroplate->addSection('Mll\Microplate\FullColumnSection');
        self::assertCount(1, $sectionedMicroplate->sections);
        self::assertCount(96, $sectionedMicroplate->freeWells());

        foreach (range(1, 4) as $ignored1) {
            $section1->addWell('section1');
        }

        $section2 = $sectionedMicroplate->addSection('Mll\Microplate\FullColumnSection');
        $emptyCoordinateInSection1 = new Coordinate('E', 1, $coordinateSystem);
        self::assertSame(FullColumnSection::RESERVED_WELL, $sectionedMicroplate->well($emptyCoordinateInSection1));

        foreach (range(1, 5) as $ignored1) {
            $section2->addWell('section2');
        }
        self::assertSame(FullColumnSection::RESERVED_WELL, $sectionedMicroplate->well($emptyCoordinateInSection1));

        self::assertSame([
            'A1' => 'section1',
            'B1' => 'section1',
            'C1' => 'section1',
            'D1' => 'section1',
            'E1' => 'reserved',
            'F1' => 'reserved',
            'G1' => 'reserved',
            'H1' => 'reserved',
            'A2' => 'section2',
            'B2' => 'section2',
            'C2' => 'section2',
            'D2' => 'section2',
            'E2' => 'section2',
            'F2' => 'reserved',
            'G2' => 'reserved',
            'H2' => 'reserved',
        ], $sectionedMicroplate->filledWells()->toArray());

        foreach (range(1, 16) as $ignored1) {
            $section1->addWell('section1');
        }

        self::assertSame([
            'A1' => 'section1',
            'B1' => 'section1',
            'C1' => 'section1',
            'D1' => 'section1',
            'E1' => 'section1',
            'F1' => 'section1',
            'G1' => 'section1',
            'H1' => 'section1',
            'A2' => 'section1',
            'B2' => 'section1',
            'C2' => 'section1',
            'D2' => 'section1',
            'E2' => 'section1',
            'F2' => 'section1',
            'G2' => 'section1',
            'H2' => 'section1',
            'A3' => 'section1',
            'B3' => 'section1',
            'C3' => 'section1',
            'D3' => 'section1',
            'E3' => 'reserved',
            'F3' => 'reserved',
            'G3' => 'reserved',
            'H3' => 'reserved',
            'A4' => 'section2',
            'B4' => 'section2',
            'C4' => 'section2',
            'D4' => 'section2',
            'E4' => 'section2',
            'F4' => 'reserved',
            'G4' => 'reserved',
            'H4' => 'reserved',
        ], $sectionedMicroplate->filledWells()->toArray());

        $this->expectExceptionObject(new SectionIsFullException());

        foreach (range(1, 100) as $ignored1) {
            $section1->addWell('section1');
        }
    }
}

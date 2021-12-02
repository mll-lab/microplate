<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit\SectionedMicroplate;

use Mll\Microplate\AbstractSection;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Section;
use Mll\Microplate\SectionedMicroplate;
use PHPUnit\Framework;

class SectionedMicroplateTest extends Framework\TestCase
{
    public function testCanAddSectionsAndWellsToSectionAndRemoveSections(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $section1 = $sectionedMicroplate->addSection(Section::class);
        self::assertCount(1, $sectionedMicroplate->sections);

        $section2 = $sectionedMicroplate->addSection(Section::class);
        self::assertCount(2, $sectionedMicroplate->sections);

        self::assertCount(0, $sectionedMicroplate->filledWells());
        self::assertCount(96, $sectionedMicroplate->freeWells());

        $content1 = 'content1';
        $section1->addWell($content1);
        $content2 = 'content2';
        $content3 = 'content3';
        $section2->addWell($content2);
        $section2->addWell($content3);

        $this->assertSectionAndWellContent($sectionedMicroplate, $content1, $section1, $content2, $section2, $content3);
    }

    public function testCanAddNamedSectionsAndWellsToSectionAndRemoveSections(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $sectionedMicroplate = new SectionedMicroplate($coordinateSystem);
        self::assertCount(0, $sectionedMicroplate->sections);

        $firstSectionName = 'firstSectionName';
        $sectionedMicroplate->addNamedSection(Section::class, $firstSectionName);
        self::assertCount(1, $sectionedMicroplate->sections);

        $secondSectionName = 'secondSectionName';
        $sectionedMicroplate->addNamedSection(Section::class, $secondSectionName);
        self::assertCount(2, $sectionedMicroplate->sections);

        self::assertCount(0, $sectionedMicroplate->filledWells());
        self::assertCount(96, $sectionedMicroplate->freeWells());

        // test the section can be retrieved by name
        $section1 = $sectionedMicroplate->sections[$firstSectionName];
        $section2 = $sectionedMicroplate->sections[$secondSectionName];

        if (! $section1 instanceof AbstractSection || ! $section2 instanceof AbstractSection) {
            self::fail('sections could not be found');
        }

        $content1 = 'content1';
        $section1->addWell($content1);
        $content2 = 'content2';
        $content3 = 'content3';
        $section2->addWell($content2);
        $section2->addWell($content3);

        $this->assertSectionAndWellContent($sectionedMicroplate, $content1, $section1, $content2, $section2, $content3);
    }

    private function assertSectionAndWellContent(SectionedMicroplate $sectionedMicroplate, string $content1, AbstractSection $section1, string $content2, AbstractSection $section2, string $content3): void
    {
        self::assertCount(3, $sectionedMicroplate->filledWells());
        self::assertCount(93, $sectionedMicroplate->freeWells());

        self::assertSame($content1, $section1->sectionItems->first());

        self::assertSame($content2, $section2->sectionItems->first());
        self::assertSame($content3, $section2->sectionItems->last());

        $sectionedMicroplate->removeSection($section1);
        self::assertCount(1, $sectionedMicroplate->sections);

        self::assertCount(2, $sectionedMicroplate->filledWells());
        self::assertCount(94, $sectionedMicroplate->freeWells());
    }
}

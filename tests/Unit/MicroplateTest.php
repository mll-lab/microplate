<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Unit;

use Illuminate\Support\Collection;
use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem12Well;
use Mll\Microplate\CoordinateSystem96Well;
use Mll\Microplate\Enums\FlowDirection;
use Mll\Microplate\Exceptions\MicroplateIsFullException;
use Mll\Microplate\Microplate;
use PHPUnit\Framework;

class MicroplateTest extends Framework\TestCase
{
    public function testCanAddAndRetriedWellBasedOnCoordinateSystem(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();

        $microplate = new Microplate($coordinateSystem);

        $microplateCoordinate1 = new Coordinate('A', 2, $coordinateSystem);
        $microplateCoordinate2 = new Coordinate('A', 3, $coordinateSystem);

        $wellContent1 = 'foo';
        $microplate->addWell($microplateCoordinate1, $wellContent1);

        $wellContent2 = 'bar';
        $microplate->addWell($microplateCoordinate2, $wellContent2);

        self::assertEquals($wellContent1, $microplate->well($microplateCoordinate1));
        self::assertEquals($wellContent2, $microplate->well($microplateCoordinate2));

        $coordinateWithOtherCoordinateSystem = new Coordinate('B', 2, new CoordinateSystem12Well());
        // @phpstan-ignore-next-line expecting a type error due to mismatching coordinates
        $microplate->addWell($coordinateWithOtherCoordinateSystem, 'foo');
    }

    public function testSortedWells(): void
    {
        $microplate = $this->preparePlate();

        /** @var Collection<int, string> $keysSortedByRow PHPStan is wrong about what keys() does */
        $keysSortedByRow = $microplate->sortedWells(FlowDirection::ROW())->keys();
        self::assertSame('A1', $keysSortedByRow[0]);
        self::assertSame('A2', $keysSortedByRow[1]);
        self::assertSame('A3', $keysSortedByRow[2]);
        self::assertSame('A4', $keysSortedByRow[3]);
        self::assertSame('H11', $keysSortedByRow[94]);
        self::assertSame('H12', $keysSortedByRow[95]);

        /** @var Collection<int, string> $keysSortedByColumn PHPStan is wrong about what keys() does */
        $keysSortedByColumn = $microplate->sortedWells(FlowDirection::COLUMN())->keys();
        self::assertSame('A1', $keysSortedByColumn[0]);
        self::assertSame('B1', $keysSortedByColumn[1]);
        self::assertSame('C1', $keysSortedByColumn[2]);
        self::assertSame('D1', $keysSortedByColumn[3]);
        self::assertSame('G12', $keysSortedByColumn[94]);
        self::assertSame('H12', $keysSortedByColumn[95]);
    }

    public function testFreeWells(): void
    {
        $microplate = $this->preparePlate();

        self::assertTrue(
            $microplate->wells()->some(
                /**
                 * @param mixed|null $value
                 */
                static fn ($value): bool => null !== $value
            )
        );
        self::assertNotCount(0, $microplate->freeWells());
    }

    /**comp
     * @phpstan-return Microplate<mixed, CoordinateSystem96Well>
     */
    private function preparePlate(): Microplate
    {
        $coordinateSystem = new CoordinateSystem96Well();

        $microplate = new Microplate($coordinateSystem);

        $dataProvider96Well = CoordinateTest::dataProvider96Well();
        \Safe\shuffle($dataProvider96Well);
        foreach ($dataProvider96Well as $wellData) {
            $microplateCoordinate = new Coordinate($wellData['row'], $wellData['column'], new CoordinateSystem96Well());

            $randomNumber = rand(1, 100);
            $randomNumberOrNull = $randomNumber > 50 ? $randomNumber : null;

            $microplate->addWell($microplateCoordinate, $randomNumberOrNull);
        }

        return $microplate;
    }

    public function testNextFreeWellAddingAndGetting(): void
    {
        $coordinateSystem = new CoordinateSystem96Well();
        $microplate = new Microplate($coordinateSystem);

        $wellData = [
            'A1' => 'foo',
            'B1' => 'bar',
            'A2' => 'foobar',
            'A3' => 'barfoo',
        ];

        $coordinateString1 = array_keys($wellData)[0];
        $microplateCoordinate1 = Coordinate::fromString($coordinateString1, $coordinateSystem);
        self::assertEquals($microplateCoordinate1, $microplate->nextFreeWellCoordinate(FlowDirection::COLUMN()));
        $microplate->addToNextFreeWell($wellData[$coordinateString1], FlowDirection::COLUMN());

        $coordinateString2 = array_keys($wellData)[1];
        $microplateCoordinate2 = Coordinate::fromString($coordinateString2, $coordinateSystem);
        self::assertEquals($microplateCoordinate2, $microplate->nextFreeWellCoordinate(FlowDirection::COLUMN()));
        $microplate->addToNextFreeWell($wellData[$coordinateString2], FlowDirection::COLUMN());

        $microplateCoordinate3 = Coordinate::fromString('C1', $coordinateSystem);
        self::assertEquals($microplateCoordinate3, $microplate->nextFreeWellCoordinate(FlowDirection::COLUMN()));

        $coordinateString4 = array_keys($wellData)[2];
        $microplateCoordinate4 = Coordinate::fromString($coordinateString4, $coordinateSystem);
        self::assertEquals($microplateCoordinate4, $microplate->addToNextFreeWell($wellData[$coordinateString4], FlowDirection::ROW()));

        $coordinateString5 = array_keys($wellData)[3];
        $microplateCoordinate5 = Coordinate::fromString($coordinateString5, $coordinateSystem);
        self::assertEquals($microplateCoordinate5, $microplate->addToNextFreeWell($wellData[$coordinateString5], FlowDirection::ROW()));

        self::assertSame($wellData, $microplate->filledWells()->toArray());
    }

    public function testThrowsPlateFullException(): void
    {
        $coordinateSystem = new CoordinateSystem12Well();
        $microplate = new Microplate($coordinateSystem);

        $dataProvider12Well = self::dataProvider12Well();
        foreach ($dataProvider12Well as $wellData) {
            $microplateCoordinate = new Coordinate($wellData['row'], $wellData['column'], $coordinateSystem);
            // check that it does not throw before the plate is full
            self::assertEquals($microplateCoordinate, $microplate->nextFreeWellCoordinate(FlowDirection::ROW()));
            $microplate->addWell($microplateCoordinate, rand(1, 100));
        }

        $this->expectException(MicroplateIsFullException::class);
        $microplate->nextFreeWellCoordinate(FlowDirection::ROW());
    }

    /**
     * @return list<array{row: string, column: int, rowFlowPosition: int, columnFlowPosition: int}>
     */
    public function dataProvider96Well(): array
    {
        return CoordinateTest::dataProvider96Well();
    }

    /**
     * @return list<array{row: string, column: int, rowFlowPosition: int, columnFlowPosition: int}>
     */
    public static function dataProvider12Well(): array
    {
        return CoordinateTest::dataProvider12Well();
    }
}

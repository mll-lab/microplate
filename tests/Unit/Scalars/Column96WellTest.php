<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Scalars\Unit;

use GraphQL\Error\Error;
use Mll\Microplate\Scalars\Column96Well;
use PHPUnit\Framework\TestCase;

final class Column96WellTest extends TestCase
{
    public function testSerializeThrowsIfColumn96WellIsNotAnInt(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value not in range: 12');

        (new Column96Well())->serialize('12');
    }

    public function testSerializeThrowsIfColumn96WellIsInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value not in range: 13');

        (new Column96Well())->serialize(13);
    }

    public function testSerializePassesWhenColumn96WellIsValid(): void
    {
        $serializedResult = (new Column96Well())->serialize(12);

        self::assertSame(12, $serializedResult);
    }

    public function testParseValueThrowsIfColumn96WellIsInvalid(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Value not in range: 13');

        (new Column96Well())->parseValue(13);
    }

    public function testParseValuePassesIfColumn96WellIsValid(): void
    {
        self::assertSame(
            12,
            (new Column96Well())->parseValue(12)
        );
    }
}

<?php declare(strict_types=1);

namespace Mll\Microplate\Tests\Scalars\Unit;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use Mll\Microplate\Scalars\Row96Well;
use PHPUnit\Framework\TestCase;

final class Row96WellTest extends TestCase
{
    public function testSerializeThrowsIfRow96WellIsInvalid(): void
    {
        $this->expectException(InvariantViolation::class);
        $this->expectExceptionMessage('The given value "I" did not match the regex /^[A-H]$/.');

        (new Row96Well())->serialize('I');
    }

    public function testSerializeThrowsIfRow96WellIsNonCapital(): void
    {
        $this->expectException(InvariantViolation::class);
        $this->expectExceptionMessage('The given value "h" did not match the regex /^[A-H]$/.');

        (new Row96Well())->serialize('h');
    }

    public function testSerializePassesWhenRow96WellIsValid(): void
    {
        $serializedResult = (new Row96Well())->serialize('H');

        self::assertSame('H', $serializedResult);
    }

    public function testParseValueThrowsIfRow96WellIsInvalid(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('The given value "I" did not match the regex /^[A-H]$/.');

        (new Row96Well())->parseValue('I');
    }

    public function testParseValueThrowsIfRow96WellIsNonCapital(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('The given value "h" did not match the regex /^[A-H]$/.');

        (new Row96Well())->parseValue('h');
    }

    public function testParseValuePassesIfRow96WellIsValid(): void
    {
        self::assertSame(
            'H',
            (new Row96Well())->parseValue('H')
        );
    }
}

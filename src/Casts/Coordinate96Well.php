<?php declare(strict_types=1);

namespace Mll\Microplate\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Mll\Microplate\Coordinate;
use Mll\Microplate\CoordinateSystem96Well;

/**
 * @implements CastsAttributes<Coordinate<CoordinateSystem96Well>, Coordinate<CoordinateSystem96Well>>
 */
final class Coordinate96Well implements CastsAttributes
{
    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed> $attributes
     *
     * @return Coordinate<CoordinateSystem96Well>
     */
    public function get($model, $key, $value, $attributes): Coordinate
    {
        assert(is_string($value));

        return Coordinate::fromString($value, new CoordinateSystem96Well());
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  array<array-key, mixed>  $attributes
     */
    public function set($model, $key, $value, $attributes): string
    {
        assert($value instanceof Coordinate);

        return $value->toString();
    }
}

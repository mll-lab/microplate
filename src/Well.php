<?php

declare(strict_types=1);

namespace Mll\Microplate;

/**
 * @phpstan-template T
 */
class Well
{
    public Coordinate $coodinate;
    public $content;

    /**
     * @phpstan-param T $content
     */
    public function __construct(Coordinate $coodinate, $content)
    {
        $this->coodinate = $coodinate;
        $this->content = $content;
    }
}

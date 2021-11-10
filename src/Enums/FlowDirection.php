<?php

declare(strict_types=1);

namespace Mll\Microplate\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static FlowDirection ROW()
 * @method static FlowDirection COLUMN()
 */
final class FlowDirection extends Enum
{
    private const ROW = 'ROW';
    private const COLUMN = 'COLUMN';
}

<?php

declare(strict_types=1);

namespace Mll\Microplate\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static static ROW()
 * @method static static COLUMN()
 */
final class FlowDirection extends Enum
{
    public const ROW = 'ROW';
    public const COLUMN = 'COLUMN';
}

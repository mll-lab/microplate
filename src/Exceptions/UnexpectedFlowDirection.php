<?php declare(strict_types=1);

namespace Mll\Microplate\Exceptions;

use Mll\Microplate\Enums\FlowDirection;

final class UnexpectedFlowDirection extends \UnexpectedValueException
{
    public function __construct(FlowDirection $flowDirection)
    {
        parent::__construct('Unexpected flow direction value:' . $flowDirection->getValue());
    }
}

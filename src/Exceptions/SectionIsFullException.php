<?php declare(strict_types=1);

namespace Mll\Microplate\Exceptions;

final class SectionIsFullException extends \UnexpectedValueException
{
    public function __construct()
    {
        parent::__construct('No free spots left on section');
    }
}

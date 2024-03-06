<?php

namespace Soandso\Synop\Exception;

use Exception;
use Throwable;

class CountSymbolException extends Exception
{
    private string $group;

    public function __construct(
        string $group,
        $message = "The meteorological summary group has an invalid number of characters",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->group = $group;
    }

    public function getGroup(): string
    {
        return $this->group;
    }
}

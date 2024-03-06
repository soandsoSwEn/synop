<?php

namespace Soandso\Synop\Exception;

use Exception;
use Throwable;

class EndSignException extends Exception
{
    private string $report;

    public function __construct(
        string $report,
        $message = "There is no sign of the end of the meteorological report",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->report = $report;
    }

    public function getReport(): string
    {
        return $this->report;
    }
}

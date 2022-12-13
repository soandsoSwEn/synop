<?php

namespace Soandso\Synop\Exception;

use Exception;
use Throwable;

class EmptyReportException extends Exception
{
    private string $report;

    public function __construct(string $report, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->report = $report;
    }

    public function getReport(): string
    {
        return $this->report;
    }
}

<?php

namespace Synop\Fabrication;

use Synop\Fabrication\Validate;

/**
 * Description of Validate
 *
 * @author Vyacheslav
 */
class Validate implements ValidateInterface
{
    private $report;
    
    public function __construct(string $report)
    {
        $this->report = $this->preparation($report);
    }

    public function preparation(string $report): string
    {
        return $this->clearDoubleSpacing($report);
    }
    
    public function isValid(string $report): bool
    {
        //
    }
    
    public function clearDoubleSpacing(string $report) : string
    {
        return preg_replace("/[  ]+/", " ", $report);
    }
}

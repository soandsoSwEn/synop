<?php

namespace Synop\Fabrication;

/**
 * This interface describes methods for validating weather reports
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ValidateInterface
{
    public function preparation(string $report) : string;
    
    public function isValid(string $report) : bool;
}

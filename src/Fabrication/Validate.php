<?php

namespace Synop\Fabrication;


/**
 * Description of Validate
 *
 * @author Vyacheslav
 */
class Validate implements ValidateInterface
{
    private $report;
    
    private $distinctive_groups = [
        'AAXX', 'BBXX', '222', 'ICE', '333', '444', '555'
    ];


    public function __construct(string $report)
    {
        $this->report = $this->preparation($report);
    }

    public function preparation(string $report): string
    {
        return $this->clearDoubleSpacing($report);
    }
    
    public function isValid(): bool
    {
        if(!$this->report) {
            throw new Exception('Meteorological weather report not defined!');
        }
        return $this->isEndEqualSign($this->report) && $this->isCountSymbol($this->report) ? true : false;
    }
    
    public function clearDoubleSpacing(string $report) : string
    {
        return preg_replace("/[  ]+/", " ", $report);
    }
    
    public function isEndEqualSign(string $report) : bool
    {
        $count_submbol_report = iconv_strlen($report);
        $end_sumbol_position = stripos( $report, "=");
        
        return ($count_submbol_report-1) == $end_sumbol_position ? true : false;
    }
    
    public function isCountSymbol(string $report) : bool
    {
        $this->report = str_replace('=', '', $report);
        $groups = explode(' ', $this->report);
        foreach ($groups as $group) {
            if(!$this->isGroup($group)) {
                return false;
            }
        }
        
        return true;
    }
    
    public function isGroup(string $group) : bool
    {
        if(iconv_strlen($group) == 5) {
            return true;
        } elseif($this->isSpecificGroup($group)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isSpecificGroup(string $group) : bool
    {
        foreach ($this->distinctive_groups as $groups) {
            if(strcmp($groups, $group) == 0) {
                return true;
            }
        }
        
        return false;
    }
}

<?php

namespace Synop;

/**
 * This interface describes basic operations with the weather report 
 * AAXX/BBXX (Synop/Ship)
 * 
 * @see https://www.wmo.int/pages/prog/www/WMOCodes/WMO306_vI1/Publications/2016update/WMO306_vI1_en_2011UP2016.pdf Meteorological code documentation
 * @see http://www.met.wur.nl/education/atmospract/unit1/plot%20manual.pdf Synop code plot in map
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ReportInterface
{
    public function setReport(string $report) : void;
    
    public function validate() : mixed;
    
    public function getType() : string;
    
    public function getWmo() : string;

    public function parse() : object;
}

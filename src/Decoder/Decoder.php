<?php

namespace Synop\Decoder;

use Synop\Fabrication\RawReportInterface;

/**
 * Description of Decoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Decoder
{
    const SETION_TWO = 'Section Two';
    const SECTION_THREE = 'Section Three';
    const SECTION_FOUR = 'Section Four';
    const SECTION_FIVE = 'Section Five';

    public function block(string $report_data) : string
    {
        return strstr($report_data, ' ', true);
    }
    
    public function endBlock(string $report_data) : string
    {
        return strstr($report_data, '=', true);
    }
    
    public function updateReport(string $group, RawReportInterface $raw_report) : void
    {
        $report = str_replace($group . ' ', '', $raw_report->getReport());
        $raw_report->updateReport($report);
    }
}

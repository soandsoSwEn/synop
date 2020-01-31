<?php

namespace Synop\Decoder;

use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionFourDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionFourDecoder implements DecoderInterface
{
    private $synop_report = null;
    
    private $ship_report = null;
    
    public function __construct(bool $synop, bool $ship)
    {
        $this->synop_report = $synop;
        $this->ship_report = $ship;
    }
    
    public function parse(string $report_data): object
    {
        //
    }
    
    public function getNCHHCt(RawReportInterface $raw_report)
    {
        $mountain_stations = false;
        if($this->synop_report) {
            $mountain_weather_stations = $this->block($raw_report->getReport());
            $mountain_stations = true;
            $this->updateReport($mountain_weather_stations, $raw_report);
            return $mountain_weather_stations;
        } else {
            //ship report
        }
    }
    
    public function block(string $report_data) : string
    {
        return strstr($report_data, ' ', true);
    }
    
    public function updateReport(string $group, RawReportInterface $raw_report) : void
    {
        $report = str_replace($group . ' ', '', $raw_report->getReport());
        $raw_report->updateReport($report);
    }
}

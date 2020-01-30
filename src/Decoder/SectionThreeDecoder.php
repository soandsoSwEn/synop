<?php

namespace Synop\Decoder;

use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionTheeDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionThreeDecoder implements DecoderInterface
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
    
    public function get1SnTxTxTx(RawReportInterface $raw_report)
    {
        $maximum_temperature = false;
        if($this->synop_report) {
            $maximum_temperature_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($maximum_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $maximum_temperature = true;
            }
        } else {
            //ship report
        }
        if($maximum_temperature) {
            $this->updateReport($maximum_temperature_group, $raw_report);
            return $maximum_temperature_group;
        } else {
            return null;
        }
    }
    
    public function get2SnTnTnTn(RawReportInterface $raw_report)
    {
        $minimum_temperature = false;
        if($this->synop_report) {
            $minimum_temperature_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($minimum_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $minimum_temperature = true;
            }
        } else {
            //ship report
        }
        if($minimum_temperature) {
            $this->updateReport($minimum_temperature_group, $raw_report);
            return $minimum_temperature_group;
        } else {
            return null;
        }
    }
    
    public function get6RRRtr(RawReportInterface $raw_report)
    {
        $precipitation = false;
        if($this->synop_report) {
            $precipitation_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($precipitation_group, 0, 1);
            if(strcmp($distinguishing_digit, '6') == 0) {
                $precipitation = true;
            }
        } else {
            //ship report
        }
        if($precipitation) {
            $this->updateReport($precipitation_group, $raw_report);
            return $precipitation_group;
        } else {
            return null;
        }
    }
    
    public function get8NsChshs(RawReportInterface $raw_report)
    {
        $clouds = false;
        if($this->synop_report) {
            $clouds_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($clouds_group, 0, 1);
            if(strcmp($distinguishing_digit, '8') == 0) {
                $clouds = true;
            }
        } else {
            //ship report
        }
        if($clouds) {
            $this->updateReport($clouds_group, $raw_report);
            return $clouds_group;
        } else {
            return null;
        }
    }
    
    public function get9SpSpspsp(RawReportInterface $raw_report)
    {
        $weather = false;
        if($this->synop_report) {
            $weather_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($weather_group, 0, 1);
            if(strcmp($distinguishing_digit, '9') == 0) {
                $weather = true;
            }
        } else {
            //ship report
        }
        if($weather) {
            $this->updateReport($weather_group, $raw_report);
            return $weather_group;
        } else {
            return null;
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

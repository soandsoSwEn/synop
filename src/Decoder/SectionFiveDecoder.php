<?php

namespace Synop\Decoder;

use Synop\Decoder\Decoder;
use Synop\Sheme\SectionInterface;
use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionFiveDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionFiveDecoder extends Decoder implements DecoderInterface
{
    private $section;
    
    private $synop_report = null;
    
    private $ship_report = null;
    
    public function __construct(SectionInterface $section_title, bool $synop, bool $ship)
    {
        $this->section = $section_title;
        $this->synop_report = $synop;
        $this->ship_report = $ship;
    }
    
    public function parse(): SectionInterface
    {
        return $this->section;
    }
    
    private function putInSection($data)
    {
        if($this->section->setBody($data)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get1SnT24T24T24(RawReportInterface $raw_report)
    {
        $average_temperature = false;
        if($this->synop_report) {
            $average_daily_temperature = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($average_daily_temperature, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $average_temperature = true;
            }
        } else {
            //ship report
        }
        if($average_temperature) {
            $this->updateReport($average_daily_temperature, $raw_report);
            return $this->putInSection($average_daily_temperature) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get3SnTgTg(RawReportInterface $raw_report)
    {
        $soil_temperature = false;
        if($this->synop_report) {
            $minimum_soil_temperature = $this->block($raw_report->getReport());
            $distinctive_character = substr($minimum_soil_temperature, 0, 2);
            if(strcmp($distinctive_character, '3/') == 0) {
                $soil_temperature = true;
            }
        } else {
            //ship report
        }
        if($soil_temperature) {
            $this->updateReport($minimum_soil_temperature, $raw_report);
            return $this->putInSection($minimum_soil_temperature) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get4Esss(RawReportInterface $raw_report)
    {
        $snow = false;
        if($this->synop_report) {
            $snow_cover = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($snow_cover, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $snow = true;
            }
        } else {
            //ship report
        }
        if($snow) {
            $this->updateReport($snow_cover, $raw_report);
            return $this->putInSection($snow_cover) ? true : false;
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
            return $this->putInSection($precipitation_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get7R24R24R24E(RawReportInterface $raw_report)
    {
        $precipitation = false;
        if($this->synop_report) {
            $precipitation_day = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($precipitation_day, 0, 1);
            if(strcmp($distinguishing_digit, '7') == 0) {
                $precipitation = true;
            }
        } else {
            //ship report
        }
        if($precipitation) {
            $this->updateReport($precipitation_day, $raw_report);
            return $this->putInSection($precipitation_day) ? true : false;
        } else {
            return null;
        }
    }

    public function get9SpSpspsp(RawReportInterface $raw_report)
    {
        $weather = false;
        if($this->synop_report) {
            $weather_group = $this->endBlock($raw_report->getReport());
            $distinguishing_digit = substr($weather_group, 0, 1);
            if(strcmp($distinguishing_digit, '9') == 0) {
                $weather = true;
            }
        } else {
            //ship report
        }
        if($weather) {
            $this->updateReport($weather_group, $raw_report);
            return $this->putInSection($weather_group) ? true : false;
        } else {
            return null;
        }
    }
}

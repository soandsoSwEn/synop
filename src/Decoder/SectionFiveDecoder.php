<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;

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

    public function isGroup(string $codeFigure, int $size) : bool
    {
        return mb_strlen($codeFigure) === $size;
    }
    
    public function parse(): SectionInterface
    {
        return $this->section;
    }
    
    private function putInSection($data)
    {
        $this->section->setBody($data);

        return true;
    }
    
    public function get1SnT24T24T24(RawReportInterface $raw_report)
    {
        $average_temperature = false;
        if($this->synop_report) {
            $average_daily_temperature = $this->block($raw_report->getReport());
            if (!$this->isGroup($average_daily_temperature, 5)) {
                return null;
            }

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
            if (!$this->isGroup($minimum_soil_temperature, 5)) {
                return null;
            }

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
            if (!$this->isGroup($snow_cover, 5)) {
                return null;
            }

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
            if (!$this->isGroup($precipitation_group, 5)) {
                return null;
            }

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
            if (!$this->isGroup($precipitation_day, 5)) {
                return null;
            }

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
            if (!$this->isGroup($weather_group, 5)) {
                return null;
            }

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

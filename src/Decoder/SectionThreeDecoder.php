<?php

namespace Synop\Decoder;

use Synop\Decoder\Decoder;
use Synop\Sheme\SectionInterface;
use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionTheeDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionThreeDecoder extends Decoder implements DecoderInterface
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
            return $this->putInSection($maximum_temperature_group) ? true : false;
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
            return $this->putInSection($minimum_temperature_group) ? true : false;
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
            return $this->putInSection($clouds_group) ? true : false;
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
            return $this->putInSection($weather_group) ? true : false;
        } else {
            return null;
        }
    }
}

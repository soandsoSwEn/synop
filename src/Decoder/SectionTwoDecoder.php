<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Sheme\SectionInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionTwoDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionTwoDecoder extends Decoder implements DecoderInterface
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

    /**
     * Returns the result of checking if the group of the code matches the group of the weather report
     * @param string $codeFigure
     * @param int $size Weather group size
     * @return bool
     */
    public function isGroup(string $codeFigure, int $size): bool
    {
        return mb_strlen($codeFigure) === $size;
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
    
    public function get222DsVs(RawReportInterface $raw_report)
    {
        $section_two = false;
        if($this->synop_report) {
            $section_two_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($section_two_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($section_two_group, 0, 3);
            if(strcmp($distinguishing_digit, '222') == 0) {
                $section_two = true;
            }
        } else {
            //ship report
        }
        if($section_two) {
            $this->updateReport($section_two_group, $raw_report);
            return $this->putInSection($section_two_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get0SnTwTwTw(RawReportInterface $raw_report)
    {
        $sea_temperature = true;
        if($this->synop_report) {
            $sea_temperature_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($sea_temperature_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($sea_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '0') == 0) {
                $sea_temperature = true;
            }
        } else {
            //ship report
        }
        if($sea_temperature) {
            $this->updateReport($sea_temperature_group, $raw_report);
            return $this->putInSection($sea_temperature_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get1PwaPwaHwaHwa(RawReportInterface $raw_report)
    {
        $sea_wave = false;
        if($this->synop_report) {
            $sea_wave_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($sea_wave_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($sea_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $sea_wave = true;
            }
        } else {
            //ship report
        }
        if($sea_wave) {
            $this->updateReport($sea_wave_group, $raw_report);
            return $this->putInSection($sea_wave_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get2PwPwHwHw(RawReportInterface $raw_report)
    {
        $wind_waves = false;
        if($this->synop_report) {
            $wind_waves_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($wind_waves_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($wind_waves_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $wind_waves = true;
            }
        } else {
            //ship report
        }
        if($wind_waves) {
            $this->updateReport($wind_waves_group, $raw_report);
            return $this->putInSection($wind_waves_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get3dw1dw1dw2dw2(RawReportInterface $raw_report)
    {
        $wave_transference = false;
        if($this->synop_report) {
            $wave_transference_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($wave_transference_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($wave_transference_group, 0, 1);
            if(strcmp($distinguishing_digit, '3') == 0) {
                $wave_transference = true;
            }
        } else {
            //ship report
        }
        if($wave_transference) {
            $this->updateReport($wave_transference_group, $raw_report);
            return $this->putInSection($wave_transference_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get4Pw1Pw1Hw1Hw1(RawReportInterface $raw_report)
    {
        $period_height_wave = false;
        if($this->synop_report) {
            $period_height_wind_wave_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($period_height_wind_wave_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($period_height_wind_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $period_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_height_wave) {
            $this->updateReport($period_height_wind_wave_group, $raw_report);
            return $this->putInSection($period_height_wind_wave_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get5Pw2Pw2Hw2Hw2(RawReportInterface $raw_report)
    {
        $period_and_height_wave = false;
        if($this->synop_report) {
            $period_height_wave_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($period_height_wave_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($period_height_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '5') == 0) {
                $period_and_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_and_height_wave) {
            $this->updateReport($period_height_wave_group, $raw_report);
            return $this->putInSection($period_height_wave_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get6IsEsEsPs(RawReportInterface $raw_report)
    {
        $period_and_height_wave = false;
        if($this->synop_report) {
            $vessel_icing_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($vessel_icing_group, 5)) {
                return null;
            }

            $distinguishing_digit = substr($vessel_icing_group, 0, 1);
            if(strcmp($distinguishing_digit, '6') == 0) {
                $period_and_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_and_height_wave) {
            $this->updateReport($vessel_icing_group, $raw_report);
            return $this->putInSection($vessel_icing_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function getISE(RawReportInterface $raw_report)
    {
        $ice = false;
        if($this->synop_report) {
            $distinguishing_word_ice = $this->block($raw_report->getReport());
            if (!$this->isGroup($distinguishing_word_ice, 3)) {
                return null;
            }

            if(strcmp($distinguishing_word_ice, 'ICE') == 0) {
                $ice = true;
                $this->updateReport($distinguishing_word_ice, $raw_report);
                $ice_group = $this->getciSibiDizi($raw_report);
                return $this->putInSection($ice_group) ? true : false;
            }
        } else {
            //ship report
        }
        return null;
    }
    
    public function getciSibiDizi(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $ice_group = $this->block($raw_report->getReport());
            if (!$this->isGroup($ice_group, 5)) {
                return null;
            }

        } else {
            //ship report
        }
        $this->updateReport($ice_group, $raw_report);
        return $ice_group;
    }
}

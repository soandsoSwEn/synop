<?php

namespace Synop\Decoder;

use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;

/**
 * Description of SectionTwoDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SectionTwoDecoder implements DecoderInterface
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
    
    public function get222DsVs(RawReportInterface $raw_report)
    {
        $section_two = false;
        if($this->synop_report) {
            $section_two_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($section_two_group, 0, 3);
            if(strcmp($distinguishing_digit, '222') == 0) {
                $section_two = true;
            }
        } else {
            //ship report
        }
        if($section_two) {
            $this->updateReport($section_two_group, $raw_report);
            return $section_two_group;
        } else {
            return null;
        }
    }
    
    public function get0SnTwTwTw(RawReportInterface $raw_report)
    {
        $sea_temperature = true;
        if($this->synop_report) {
            $sea_temperature_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($sea_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '0') == 0) {
                $sea_temperature = true;
            }
        } else {
            //ship report
        }
        if($sea_temperature) {
            $this->updateReport($sea_temperature_group, $raw_report);
            return $sea_temperature_group;
        } else {
            return null;
        }
    }
    
    public function get1PwaPwaHwaHwa(RawReportInterface $raw_report)
    {
        $sea_wave = false;
        if($this->synop_report) {
            $sea_wave_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($sea_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $sea_wave = true;
            }
        } else {
            //ship report
        }
        if($sea_wave) {
            $this->updateReport($sea_wave_group, $raw_report);
            return $sea_wave_group;
        } else {
            return null;
        }
    }
    
    public function get2PwPwHwHw(RawReportInterface $raw_report)
    {
        $wind_waves = false;
        if($this->synop_report) {
            $wind_waves_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($wind_waves_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $wind_waves = true;
            }
        } else {
            //ship report
        }
        if($wind_waves) {
            $this->updateReport($wind_waves_group, $raw_report);
            return $wind_waves_group;
        } else {
            return null;
        }
    }
    
    public function get3dw1dw1dw2dw2(RawReportInterface $raw_report)
    {
        $wave_transference = false;
        if($this->synop_report) {
            $wave_transference_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($wave_transference_group, 0, 1);
            if(strcmp($distinguishing_digit, '3') == 0) {
                $wave_transference = true;
            }
        } else {
            //ship report
        }
        if($wave_transference) {
            $this->updateReport($wave_transference_group, $raw_report);
            return $wave_transference_group;
        } else {
            return null;
        }
    }
    
    public function get4Pw1Pw1Hw1Hw1(RawReportInterface $raw_report)
    {
        $period_height_wave = false;
        if($this->synop_report) {
            $period_height_wind_wave_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($period_height_wind_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $period_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_height_wave) {
            $this->updateReport($period_height_wind_wave_group, $raw_report);
            return $period_height_wind_wave_group;
        } else {
            return null;
        }
    }
    
    public function get5Pw2Pw2Hw2Hw2(RawReportInterface $raw_report)
    {
        $period_and_height_wave = false;
        if($this->synop_report) {
            $period_height_wave_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($period_height_wave_group, 0, 1);
            if(strcmp($distinguishing_digit, '5') == 0) {
                $period_and_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_and_height_wave) {
            $this->updateReport($period_height_wave_group, $raw_report);
            return $period_height_wave_group;
        } else {
            return null;
        }
    }
    
    public function get6IsEsEsPs(RawReportInterface $raw_report)
    {
        $period_and_height_wave = false;
        if($this->synop_report) {
            $vessel_icing_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($vessel_icing_group, 0, 1);
            if(strcmp($distinguishing_digit, '6') == 0) {
                $period_and_height_wave = true;
            }
        } else {
            //ship report
        }
        if($period_and_height_wave) {
            $this->updateReport($vessel_icing_group, $raw_report);
            return $vessel_icing_group;
        } else {
            return null;
        }
    }
    
    public function getISE(RawReportInterface $raw_report)
    {
        $ice = false;
        if($this->synop_report) {
            $distinguishing_word_ice = $this->block($raw_report->getReport());
            if(strcmp($distinguishing_word_ice, 'ICE') == 0) {
                $ice = true;
                $this->updateReport($distinguishing_word_ice, $raw_report);
                return $this->getciSibiDizi($raw_report);
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
        } else {
            //ship report
        }
        $this->updateReport($ice_group, $raw_report);
        return $ice_group;
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

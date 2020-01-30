<?php

namespace Synop\Decoder;

use Synop\Decoder\DecoderInterface;
use Synop\Fabrication\RawReportInterface;
use Exception;
use Synop\Process\Pipeline;
use Synop\Decoder\SectionTwoDecoder;

/**
 * Identifies decoding and determines the meta information of the weather
 * report, its sections and groups of each section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GeneralDecoder implements DecoderInterface
{
    private $synop_report = null;
    
    private $ship_report = null;
    
    private $type_report = ['AAXX', 'BBXX'];
    
    public function __construct()
    {
        //
    }

    public function parse(string $report_data): object
    {
        //
    }
    
    public function getType(RawReportInterface $raw_report)
    {
        $type = $this->block($raw_report->getReport());
        if(!in_array($type, $this->type_report)) {
            throw new Exception('Weather report type not set correctly!');
        }
        if(strcmp($type, 'AAXX') == 0) {
            $this->synop_report = true;
            $this->ship_report = false;
        } elseif(strcmp($type, 'BBXX') == 0) {
            $this->synop_report = false;
            $this->ship_report = true;
        }
        $this->updateReport($type, $raw_report);
        return $type;
    }
    
    public function block(string $report_data) : string
    {
        return strstr($report_data, ' ', true);
    }
    
    public function getShipSign(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            return;
        }
        $ship_call = $this->block($raw_report->getReport());
        if(ctype_digit($ship_call)) {
            $this->updateReport($ship_call, $raw_report);
            return $ship_call;
        } else {
            throw new Exception('Invalid ship call sign format!');
        }
    }
    
    public function getYYGGiw(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $date_group = $this->block($raw_report->getReport());
        } else {
            //ship report
        }
        $this->updateReport($date_group, $raw_report);
        return $date_group;
    }
    
    public function getIIiii(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $station_index = $this->block($raw_report->getReport());
        } else {
            //ship report
        }
        $this->updateReport($station_index, $raw_report);
        return $station_index;
    }
    
    public function get99LaLaLa(RawReportInterface $raw_report)
    {
        if($this->ship_report) {
            //
        }
        return;
    }
    
    public function getQcL0L0L0L0()
    {
        if($this->ship_report) {
            //
        }
        return;
    }
    
    public function getirixhVV(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $cloud_visibility_group = $this->block($raw_report->getReport());
        } else {
            //ship report
        }
        $this->updateReport($cloud_visibility_group, $raw_report);
        return $cloud_visibility_group;
    }
    
    public function getNddff(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $cloud_visibility_group = $this->block($raw_report->getReport());
        } else {
            //ship report
        }
        $this->updateReport($cloud_visibility_group, $raw_report);
        return $cloud_visibility_group;
    }
    
    public function get1SnTTT(RawReportInterface $raw_report)
    {
        $temperature = false;
        if($this->synop_report) {
            $air_temperature_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($air_temperature_group, 0, 1);
            if(strcmp($distinguishing_digit, '1') == 0) {
                $temperature = true;
            } else {
                $temperature = false;
            }
        } else {
            //ship report
        }
        if($temperature) {
            $this->updateReport($air_temperature_group, $raw_report);
            return $air_temperature_group;
        } else {
            return null;
        }
    }
    
    public function get2SnTdTdTd(RawReportInterface $raw_report)
    {
        $dew_point = false;
        if($this->synop_report) {
            $dew_point_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($dew_point_group, 0, 1);
            if(strcmp($distinguishing_digit, '2') == 0) {
                $dew_point = true;
            }
        } else {
            //ship report
        }
        if($dew_point) {
            $this->updateReport($dew_point_group, $raw_report);
            return $dew_point_group;
        } else {
            return null;
        }
    }
    
    public function get3P0P0P0P0(RawReportInterface $raw_report)
    {
        $pressure_station = false;
        if($this->synop_report) {
            $pressure_station_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($pressure_station_group, 0, 1);
            if(strcmp($distinguishing_digit, '3') == 0) {
                $pressure_station = true;
            }
        } else {
            //ship report
        }
        if($pressure_station) {
            $this->updateReport($pressure_station_group, $raw_report);
            return $pressure_station_group;
        } else {
            return null;
        }
    }
    
    public function get4PPPP(RawReportInterface $raw_report)
    {
        $pressure_sea_level = false;
        if($this->synop_report) {
            $pressure_sea_level_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($pressure_sea_level_group, 0, 1);
            if(strcmp($distinguishing_digit, '4') == 0) {
                $pressure_sea_level = true;
            }
        } else {
            //ship report
        }
        if($pressure_sea_level) {
            $this->updateReport($pressure_sea_level_group, $raw_report);
            return $pressure_sea_level_group;
        } else {
            return null;
        }
    }
    
    public function get5appp(RawReportInterface $raw_report)
    {
        $baric_tendency = false;
        if($this->synop_report) {
            $baric_tendency_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($baric_tendency_group, 0, 1);
            if(strcmp($distinguishing_digit, '5') == 0) {
                $baric_tendency = true;
            }
        } else {
            //ship report
        }
        if($baric_tendency) {
            $this->updateReport($baric_tendency_group, $raw_report);
            return $baric_tendency_group;
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
        if($precipitation ) {
            $this->updateReport($precipitation_group, $raw_report);
            return $precipitation_group;
        } else {
            return null;
        }
    }
    
    public function get7wwW1W2(RawReportInterface $raw_report)
    {
        $weather = false;
        if($this->synop_report) {
            $weather_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($weather_group, 0, 1);
            if(strcmp($distinguishing_digit, '7') == 0) {
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
    
    public function get8NhClCmCh(RawReportInterface $raw_report)
    {
        $cloud_characteristics = false;
        if($this->synop_report) {
            $cloud_characteristics_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($cloud_characteristics_group, 0, 1);
            if(strcmp($distinguishing_digit, '8') == 0) {
                $cloud_characteristics = true;
            }
        } else {
            //ship report
        }
        if($cloud_characteristics) {
            $this->updateReport($cloud_characteristics_group, $raw_report);
            return $cloud_characteristics_group;
        } else {
            return null;
        }
    }
    
    public function get9hh(RawReportInterface $raw_report)
    {
        $cloud_height = false;
        if($this->synop_report) {
            $cloud_characteristics_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($cloud_characteristics_group, 0, 1);
            if(strcmp($distinguishing_digit, '8') == 0) {
                $cloud_height = true;
            }
        } else {
            //ship report
        }
        if($cloud_height) {
            $this->updateReport($cloud_characteristics_group, $raw_report);
            return $cloud_characteristics_group;
        } else {
            return null;
        }
    }
    
    public function get222DsVs(RawReportInterface $raw_report)
    {
        $section_two = false;
        $st_blocks = [];
        if($this->synop_report) {
            $section_two_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($section_two_group, 0, 3);
            if(strcmp($distinguishing_digit, '222') == 0) {
                $section_two = true;
                $st_pipelie = new Pipeline();
                $pipes = $this->getTwoPipes();
                $st_pipelie->pipe($pipes);
                $st_decoder = new SectionTwoDecoder($this->synop_report, $this->ship_report);
                $st_blocks[] = $st_pipelie->process($raw_report, $st_decoder);
                return $st_blocks;
            }
        } else {
            //ship report
        }
        return null;
    }
    
    public function getTwoPipes() : array
    {
        return [
            '222DsVs',
            '0SnTwTwTw',
            '1PwaPwaHwaHwa',
            '2PwPwHwHw',
            '3dw1dw1dw2dw2',
            '4Pw1Pw1Hw1Hw1',
            '5Pw2Pw2Hw2Hw2',
            '6IsEsEsPs',
            'ISE'
        ];
    }

    public function updateReport(string $group, RawReportInterface $raw_report) : void
    {
        $report = str_replace($group . ' ', '', $raw_report->getReport());
        $raw_report->updateReport($report);
    }
}

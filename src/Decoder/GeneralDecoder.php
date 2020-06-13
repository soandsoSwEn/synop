<?php

namespace Synop\Decoder;

use Synop\Decoder\Decoder;
use Synop\Decoder\DecoderInterface;
use Synop\Sheme\CloudWindGroup;
use Synop\Sheme\LowCloudVisibilityGroup;
use Synop\Sheme\SectionInterface;
use Synop\Fabrication\RawReportInterface;
use Exception;
use Synop\Process\Pipeline;
use Synop\Decoder\SectionTwoDecoder;
use Synop\Sheme\Section;
use Synop\Decoder\SectionThreeDecoder;
use Synop\Sheme\DateGroup;
use Synop\Sheme\IndexGroup;

/**
 * Identifies decoding and determines the meta information of the weather
 * report, its sections and groups of each section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GeneralDecoder extends Decoder implements DecoderInterface
{
    const ALL_SECTIONS = 'All Sections';
    
    private $sections;

    private $section;
    
    private $synop_report = null;
    
    private $ship_report = null;
    
    private $type_report = ['AAXX', 'BBXX'];
    
    public function __construct(SectionInterface $section_title)
    {
        $this->section = $section_title;
        $this->sections = new Section(self::ALL_SECTIONS);
        $this->putSection($this->section);
    }

    public function parse() : SectionInterface
    {
        return $this->sections;
    }
    
    private function putSection($data)
    {
        if($this->sections->setBody($data)) {
            return true;
        } else {
            return false;
        }
    }

    private function putInSection($data)
    {
        if($this->section->setBody($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getType(RawReportInterface $raw_report) : bool
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
        return $this->putInSection($type) ? true : false;
    }
    
    public function getShipSign(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            return;
        }
        $ship_call = $this->block($raw_report->getReport());
        if(ctype_digit($ship_call)) {
            $this->updateReport($ship_call, $raw_report);
            return $this->putInSection($ship_call) ? true : false;
        } else {
            throw new Exception('Invalid ship call sign format!');
        }
    }
    
    public function getYYGGiw(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $date_group = $this->block($raw_report->getReport());
            $date = new DateGroup($date_group);
        } else {
            //ship report
        }
        $this->updateReport($date_group, $raw_report);
        return $this->putInSection($date) ? true : false;
    }
    
    public function getIIiii(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $station_index = $this->block($raw_report->getReport());
            $index = new IndexGroup($station_index);
        } else {
            //ship report
        }
        $this->updateReport($station_index, $raw_report);
        return $this->putInSection($index) ? true : false;
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
            $iRIxHVV = new LowCloudVisibilityGroup($cloud_visibility_group);
        } else {
            //ship report
        }
        $this->updateReport($cloud_visibility_group, $raw_report);
        return $this->putInSection($iRIxHVV) ? true : false;
    }
    
    public function getNddff(RawReportInterface $raw_report)
    {
        if($this->synop_report) {
            $cloud_wind_group = $this->block($raw_report->getReport());
            $Nddff = new CloudWindGroup($cloud_wind_group);
        } else {
            //ship report
        }
        $this->updateReport($cloud_wind_group, $raw_report);
        return $this->putInSection($Nddff) ? true : false;
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
            return $this->putInSection($air_temperature_group) ? true : false;
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
            return $this->putInSection($dew_point_group) ? true : false;
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
            return $this->putInSection($pressure_station_group) ? true : false;
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
            return $this->putInSection($pressure_sea_level_group) ? true : false;
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
            return $this->putInSection($baric_tendency_group) ? true : false;
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
            return $this->putInSection($precipitation_group) ? true : false;
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
            return $this->putInSection($weather_group) ? true : false;
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
            return $this->putInSection($cloud_characteristics_group) ? true : false;
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
            return $this->putInSection($cloud_characteristics_group) ? true : false;
        } else {
            return null;
        }
    }
    
    public function get222DsVs(RawReportInterface $raw_report)
    {
        $section_two = false;
        //$st_blocks = [];
        if($this->synop_report) {
            $section_two_group = $this->block($raw_report->getReport());
            $distinguishing_digit = substr($section_two_group, 0, 3);
            if(strcmp($distinguishing_digit, '222') == 0) {
                $section_two = true;
                $st_pipelie = new Pipeline();
                $pipes = $this->getTwoPipes();
                $st_pipelie->pipe($pipes);
                $st_decoder = new SectionTwoDecoder(new Section(self::SECTION_TWO), $this->synop_report, $this->ship_report);
                $st_blocks = $st_pipelie->process($raw_report, $st_decoder);
                return $this->putSection($st_blocks) ? true : false;
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
    
    public function get333(RawReportInterface $raw_report)
    {
        $section_three_group = false;
        //$str_blocks = [];
        if($this->synop_report) {
            $section_three = $this->block($raw_report->getReport());
            if(strcmp($section_three, '333') == 0) {
                $section_three_group = true;
                $this->updateReport($section_three, $raw_report);
                $str_pipelie = new Pipeline();
                $pipes = $this->getThreePipes();
                $str_pipelie->pipe($pipes);
                $str_decoder = new SectionThreeDecoder(new Section(self::SECTION_THREE), $this->synop_report, $this->ship_report);
                $str_blocks = $str_pipelie->process($raw_report, $str_decoder);
                return $this->putSection($str_blocks) ? true : false;
            }
        } else {
            //ship report
        }
    }
    
    public function getThreePipes() : array
    {
        return [
            '1SnTxTxTx',
            '2SnTnTnTn',
            '6RRRtr',
            '8NsChshs',
            '9SpSpspsp',
        ];
    }
    
    public function get444(RawReportInterface $raw_report)
    {
        $section_four_group = false;
        //$sf_blocks = [];
        if($this->synop_report) {
            $section_four = $this->block($raw_report->getReport());
            if(strcmp($section_four, '444') == 0) {
                $section_four_group = true;
                $this->updateReport($section_four, $raw_report);
                $sf_pipelie = new Pipeline();
                $pipes = $this->getFourPipes();
                $sf_pipelie->pipe($pipes);
                $sf_decoder = new SectionFourDecoder(new Section(self::SECTION_FOUR), $this->synop_report, $this->ship_report);
                $sf_blocks = $sf_pipelie->process($raw_report, $sf_decoder);
                return $this->putSection($sf_blocks) ? true : false;
            }
        } else {
            //ship report
        }
    }
    
    public function getFourPipes() : array
    {
        return ['NCHHCt'];
    }
    
    public function get555(RawReportInterface $raw_report)
    {
        $section_five_group = false;
        //$sv_blocks = [];
        if($this->synop_report) {
            $section_five = $this->block($raw_report->getReport());
            if(strcmp($section_five, '555') == 0) {
                $section_five_group = true;
                $this->updateReport($section_five, $raw_report);
                $sv_pipelie = new Pipeline();
                $pipes = $this->getFivePipes();
                $sv_pipelie->pipe($pipes);
                $sv_decoder = new SectionFiveDecoder(new Section(self::SECTION_FIVE), $this->synop_report, $this->ship_report);
                $sv_blocks = $sv_pipelie->process($raw_report, $sv_decoder);
                return $this->putSection($sv_blocks) ? true : false;
            }
        } else {
            //ship report
        }
    }
    
    public function getFivePipes() : array
    {
        return [
            '1SnT24T24T24',
            '3SnTgTg',
            '4Esss',
            '6RRRtr',
            '7R24R24R24E',
            '9SpSpspsp',
        ];
    }
}

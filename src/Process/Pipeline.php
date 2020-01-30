<?php

namespace Synop\Process;

use Synop\Fabrication\RawReportInterface;
use Synop\Decoder\DecoderInterface;

/**
 * Description of Pipeline
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Pipeline
{
    private $pipes = [];
    
    private $blocks = [];


    public function __construct()
    {
        //
    }
    
    public function pipe($data)
    {
        $this->pipes = $data;
    }
    
    public function process(RawReportInterface $raw_report, DecoderInterface $decoder)
    {
        $this->step($raw_report, $decoder);
        return $this->blocks;
    }
    
    public function step(RawReportInterface $raw_report, DecoderInterface $decoder)
    {
        if($current_step = array_shift($this->pipes)) {
            $getter = 'get' . $current_step;
            if(method_exists($decoder, $getter)) {
                $block = $decoder->$getter($raw_report);
                if(!is_null($block)) {
                    $this->blocks[] = $block;
                }
            }
            $this->step($raw_report, $decoder);
        } else {
            return false;
        }
    }


    /*public function getPipes()
    {
        return $this->pipes;
    }*/
}

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
        return $decoder->parse();
    }
    
    public function step(RawReportInterface $raw_report, DecoderInterface $decoder)
    {
        if($current_step = array_shift($this->pipes)) {
            $getter = 'get' . $current_step;
            if(method_exists($decoder, $getter)) {
                $decoder->$getter($raw_report);
            }
            $this->step($raw_report, $decoder);
        } else {
            return false;
        }
    }
}

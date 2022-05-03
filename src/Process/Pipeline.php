<?php

namespace Soandso\Synop\Process;

use Soandso\Synop\Fabrication\RawReportInterface;
use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\ValidateInterface;
use Soandso\Synop\Sheme\SectionInterface;

/**
 * Class Pipeline contains methods for handling all groups code figure of weather report
 *
 * @package Synop\Process
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Pipeline implements PipelineInterface
{
    /**
     * @var array An ordered dataset of group names in a weather report
     */
    private $pipes = [];

    public function __construct()
    {
        //
    }

    /**
     * Adds group names in the weather report
     * @param $data array An ordered dataset of group names in a weather report
     */
    public function pipe(array $data) : void
    {
        $this->pipes = $data;
    }

    /**
     * Returns all processed sections of the meteorological report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @param DecoderInterface $decoder Decoder object for group of weather report
     * @param ValidateInterface $validate
     * @return SectionInterface
     */
    public function process(RawReportInterface $raw_report, DecoderInterface $decoder, ValidateInterface $validate) : SectionInterface
    {
        $this->step($raw_report, $decoder, $validate);
        return $decoder->parse();
    }

    /**
     * Processes groups of all sections of a given weather report
     * @param RawReportInterface $raw_report Object of meteorological report source code
     * @param DecoderInterface $decoder Decoder object for group of code of weather report
     * @return false
     */
    private function step(RawReportInterface $raw_report, DecoderInterface $decoder, ValidateInterface $validate)
    {
        if($current_step = array_shift($this->pipes)) {
            $getter = 'get' . $current_step;
            if(method_exists($decoder, $getter)) {
                $decoder->$getter($raw_report, $validate);
            }
            $this->step($raw_report, $decoder, $validate);
        } else {
            return false;
        }
    }
}

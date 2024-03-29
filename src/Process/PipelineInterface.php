<?php

namespace Soandso\Synop\Process;

use Soandso\Synop\Decoder\DecoderInterface;
use Soandso\Synop\Fabrication\RawReportInterface;
use Soandso\Synop\Fabrication\ValidateInterface;
use Soandso\Synop\Sheme\SectionInterface;

/**
 * Interface PipelineInterface must be implemented by a class that implements the processing of all blocks
 * of weather report code
 *
 * @package Synop\Process
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface PipelineInterface
{
    /**
     * Adds group names in the weather report
     *
     * @param array $data An ordered dataset of group names in a weather report
     */
    public function pipe(array $data): void;

    /**
     * Returns all processed sections of the meteorological report
     *
     * @param RawReportInterface $rawReport Object of meteorological report source code
     * @param DecoderInterface $decoder Decoder object for group of code of weather report
     * @param ValidateInterface $validate Object for decoding meteorological report
     * @return SectionInterface
     */
    public function process(
        RawReportInterface $rawReport,
        DecoderInterface $decoder,
        ValidateInterface $validate
    ): SectionInterface;
}

<?php

namespace Synop\Decoder;

use Synop\Decoder\DecoderInterface;

/**
 * identifies decoding and determines the meta information of the weather
 * report, its sections and groups of each section
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GeneralDecoder implements DecoderInterface
{
    public function parse(string $report_data): object
    {
        //
    }
}

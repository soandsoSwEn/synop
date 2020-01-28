<?php

namespace Synop\Decoder;

/**
 * The interface must be implemented in a class that performs decoding
 * of weather reports at any level (decoding of a section, group)
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface DecoderInterface
{
    /**
     * Decodes a fragment of a meteorological report into a specific entity
     * 
     * @param string $report_data
     * @return object
     */
    public function parse(string $report_data) : object;
}
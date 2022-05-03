<?php

namespace Soandso\Synop\Decoder;

use Soandso\Synop\Sheme\SectionInterface;

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
     * @return SectionInterface
     */
    public function parse() : SectionInterface;

    /**
     * Checks whether a given group of codes corresponds to a specific group of meteorological reports
     *
     * @param string $codeFigure Meteorological report group symbolic code
     * @param int $size Meteorological report group size
     * @return bool
     */
    public function isGroup(string $codeFigure, int $size) : bool;
}

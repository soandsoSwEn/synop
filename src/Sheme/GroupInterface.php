<?php

namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\ValidateInterface;

/**
 * This interface should be implemented by a class that creates a group of code of weather report.
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface GroupInterface
{
    /**
     * Sets decoder object for the given group of code of weather report
     *
     * @param GroupDecoderInterface $decoder Decoder object for the group of code of weather report
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void;

    /**
     * Sets the initial data for a weather report group and prepares for use
     *
     * @param string $data Raw symbol data of group of weather report
     * @param ValidateInterface $validate Object for decoding meteorological report
     */
    public function setData(string $data, ValidateInterface $validate) : void;

    /**
     * Returns decoder object for the given group of code of weather report
     *
     * @return GroupDecoderInterface Decoder object for the group of code of weather report
     */
    public function getDecoder() : GroupDecoderInterface;

}

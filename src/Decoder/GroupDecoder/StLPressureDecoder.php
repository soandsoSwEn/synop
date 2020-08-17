<?php


namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;


/**
 * Class StLPressureDecoder contains methods for decoding a group of
 * atmospheric pressure at the station level
 *
 * @package Synop\Decoder\GroupDecoder
 */
class StLPressureDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of atmospheric pressure at the station level group */
    const DIGIT = '3';

    /**
     * @var string Station level atmospheric pressure data
     */
    private $raw_dp_temperature;

    public function __construct(string $raw_dp_temperature)
    {
        $this->raw_dp_temperature = $raw_dp_temperature;
    }

    /**
     * Returns the result of checking the validity of the group
     * @return bool
     */
    public function isGroup() : bool
    {
        $distinguishingDigit = substr($this->raw_dp_temperature, 0, 1);

        return strcasecmp($distinguishingDigit, self::DIGIT) == 0 ? true : false;
    }

    /**
     * Returns the Station level atmospheric pressure value
     * @return float
     */
    public function getStLPressure() : float
    {
        $P0P0P0P0 = substr($this->raw_dp_temperature, 1, 4);
        $stationPressure = null;

        $firstDigit = substr($P0P0P0P0, 0, 1);
        if (strcasecmp($firstDigit, '9') == 0) {
            $intPart = substr($P0P0P0P0, 0, 3);
            $thenth = substr($P0P0P0P0, 3, 1);
            $stationPressure = floatval($intPart . '.' . $thenth);
        } else {
            $intPart = substr($P0P0P0P0, 0, 3);
            $thenth = substr($P0P0P0P0, 3, 1);
            $stationPressure = floatval('1' . $intPart . '.' . $thenth);
        }

        return $stationPressure;
    }
}
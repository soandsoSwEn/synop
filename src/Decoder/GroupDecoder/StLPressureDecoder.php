<?php


namespace Synop\Decoder\GroupDecoder;



use Exception;
use Synop\Fabrication\ValidateInterface;

/**
 * Class StLPressureDecoder contains methods for decoding a group of
 * atmospheric pressure at the station level
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class StLPressureDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of atmospheric pressure at the station level group */
    const DIGIT = '3';

    /**
     * @var string Station level atmospheric pressure data
     */
    private $raw_stl_pressure;

    public function __construct(string $raw_stl_pressure)
    {
        $this->raw_stl_pressure = $raw_stl_pressure;
    }

    /**
     * Returns the result of checking the validity of the group
     * @param ValidateInterface $validate
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate) : bool
    {
        $distinguishingDigit = substr($this->raw_stl_pressure, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(get_class($this), [$this->getCodeFigureIndicator(), $this->getCodeFigurePressure()]);
            return true;
        }

        return false;
    }

    /**
     * Returns the Station level atmospheric pressure value
     * @return float
     */
    public function getStLPressure() : float
    {
        $P0P0P0P0 = substr($this->raw_stl_pressure, 1, 4);
        $stationPressure = null;

        $firstDigit = substr($P0P0P0P0, 0, 1);
        $intPart = substr($P0P0P0P0, 0, 3);
        $thenth = substr($P0P0P0P0, 3, 1);
        if (strcasecmp($firstDigit, '9') == 0) {
            $stationPressure = floatval($intPart . '.' . $thenth);
        } else {
            $stationPressure = floatval('1' . $intPart . '.' . $thenth);
        }

        return $stationPressure;
    }

    /**
     * Return code figure of indicator of atmospheric pressure at the station level group
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->raw_stl_pressure, 0, 1);
    }

    /**
     * Return code figure of Station level atmospheric pressure
     *
     * @return false|string
     */
    private function getCodeFigurePressure()
    {
        return substr($this->raw_stl_pressure, 1, 4);
    }
}
<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

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
    protected const DIGIT = '3';

    /**
     * @var string Station level atmospheric pressure data
     */
    private $rawStlPressure;

    public function __construct(string $rawStlPressure)
    {
        $this->rawStlPressure = $rawStlPressure;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate): bool
    {
        $distinguishingDigit = substr($this->rawStlPressure, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                [$this->getCodeFigureIndicator(), $this->getCodeFigurePressure()]
            );

            return true;
        }

        return false;
    }

    /**
     * Returns the Station level atmospheric pressure value
     *
     * @return float
     */
    public function getStLPressure(): float
    {
        $P0P0P0P0 = substr($this->rawStlPressure, 1, 4);
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
     * Returns indicator and description of atmospheric pressure group at the station level - 3P0P0P0P0
     *
     * @return string[] Indicator and description of atmospheric pressure group at the station level - 3P0P0P0P0
     */
    public function getIndicatorGroup(): array
    {
        return ['3' => 'Indicator'];
    }

    /**
     * Returns indicator and description of last four figures of the air pressure - 3P0P0P0P0
     *
     * @return string[] Indicator and description of last four figures of the air pressure
     */
    public function getFigureAirPressure(): array
    {
        return [
            'PPPP' => 'Last four figures of the air pressure (reduced to mean station level) in millibars and tenths'
        ];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getIndicatorGroup()),
            key($this->getFigureAirPressure()),
        ];
    }

    /**
     * Return code figure of indicator of atmospheric pressure at the station level group
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawStlPressure, 0, 1);
    }

    /**
     * Return code figure of Station level atmospheric pressure
     *
     * @return false|string
     */
    private function getCodeFigurePressure()
    {
        return substr($this->rawStlPressure, 1, 4);
    }
}

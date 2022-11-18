<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class MslPressureDecoder contains methods for decoding a group of Air Pressure reduced to mean sea level
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class MslPressureDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of Air Pressure reduced to mean sea level */
    protected const DIGIT = '4';

    /**
     * @var string Air Pressure reduced to mean sea level data
     */
    private $rawMlsPressure;

    public function __construct(string $rawMlsPressure)
    {
        $this->rawMlsPressure = $rawMlsPressure;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate Object for weather data validation
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        $distinguishingDigit = substr($this->rawMlsPressure, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [$this->getCodeFigureIndicator(), $this->getCodeFigurePressure()]
            );

            return true;
        }

        return false;
    }

    /**
     * Returns the Air Pressure reduced to mean sea level value
     *
     * @return float
     */
    public function getMslPressure(): float
    {
        $PPPP = substr($this->rawMlsPressure, 1, 4);
        $seaLevelPressure = null;

        $firstDigit = substr($PPPP, 0, 1);
        $intPart = substr($PPPP, 0, 3);
        $thenth = substr($PPPP, 3, 1);
        if (strcasecmp($firstDigit, '9') == 0) {
            $seaLevelPressure = floatval($intPart . '.' . $thenth);
        } else {
            $seaLevelPressure = floatval('1' . $intPart . '.' . $thenth);
        }

        return $seaLevelPressure;
    }

    /**
     * Returns indicator and description of atmospheric pressure group at reduced to mean sea level - 4P0P0P0P0
     *
     * @return string[] Indicator and description of atmospheric pressure group at reduced to mean sea level - 4P0P0P0P0
     */
    public function getIndicatorGroup(): array
    {
        return ['4' => 'Indicator'];
    }

    /**
     * Returns indicator and description of last four figures of the air pressure - 3P0P0P0P0
     *
     * @return string[] Indicator and description of last four figures of the air pressure
     */
    public function getFigureAirPressure(): array
    {
        return [
            'PPPP' => 'Last four figures of the air pressure (reduced to mean sea level) in millibars and tenths'
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
     * Return code figure of indicator of air Pressure reduced to mean sea level value
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawMlsPressure, 0, 1);
    }

    /**
     * Return code figure of air Pressure reduced to mean sea level value
     *
     * @return false|string
     */
    private function getCodeFigurePressure()
    {
        return substr($this->rawMlsPressure, 1, 4);
    }
}

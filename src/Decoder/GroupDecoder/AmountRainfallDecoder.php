<?php


namespace Synop\Decoder\GroupDecoder;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;


/**
 * Class AmountRainfallDecoder contains methods for decoding a group of amount of rainfall
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AmountRainfallDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of amount of rainfall group */
    const DIGIT = '6';

    /**
     * @var string[] Main hours measurement of atmospheric precipitation
     */
    private $durationPeriodMap = [
        1 => 'At 0001 and 1200 GMT',
        2 => 'At 0600 and 1800 GMT',
    ];

    /**
     * @var string Amount of rainfall data
     */
    private $rawAmountRainfall;

    public function __construct(string $rawAmountRainfall)
    {
        $this->rawAmountRainfall = $rawAmountRainfall;
    }

    /**
     * Returns the result of checking the validity of the group
     * @return bool
     */
    public function isGroup() : bool
    {
        $distinguishingDigit = substr($this->rawAmountRainfall, 0, 1);

        return strcasecmp($distinguishingDigit, self::DIGIT) == 0 ? true : false;
    }

    public function getAmountRainfall() : array
    {
        /* String of Amount of rainfall */
        $RRR = substr($this->rawAmountRainfall, 1, 3);

        if (intval($RRR) >= 990 && intval($RRR) <= 999) {
            return $this->valueW​ithTenths($RRR);
        } else {
            return $this->integerValues($RRR);
        }
    }

    /**
     * Returns the number of duration period of Amount of rainfall
     * @return int
     */
    public function getDurationPeriodNumber() : int
    {
        return intval(substr($this->rawAmountRainfall, 4, 1));
    }

    /**
     * Returns the duration period of Amount of rainfall
     * @return string
     */
    public function getDurationPeriod() : string
    {
        $durationPeriod = intval(substr($this->rawAmountRainfall, 4, 1));

        return $this->durationPeriodMap[$durationPeriod];
    }

    /**
     * Returns the Value of Amount of rainfall
     * @param string $rainfallData String of Amount of rainfall
     * @return array
     */
    public function valueW​ithTenths(string $rainfallData) : array
    {
        $stringOfAmountRain = substr($rainfallData, 2, 1);

        if (strcasecmp($stringOfAmountRain, '990') == 0) {
            return ['Trace', null];
        }

        return [null, floatval('0.' . substr($rainfallData, 2, 1))];
    }

    /**
     * Returns the integer value of Amount of rainfall
     * @param string $rainfallData String of Amount of rainfall
     * @return array - output data of format [title, value]
     */
    public function integerValues(string $rainfallData) : array
    {
        if (strcasecmp($rainfallData, '000') == 0) {
            return ['There was no precipitation', null];
        }

        return [null, intval($rainfallData)];
    }
}
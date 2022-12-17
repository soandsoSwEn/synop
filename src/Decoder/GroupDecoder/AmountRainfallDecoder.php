<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\ValidateInterface;

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
    protected const DIGIT = '6';

    /**
     * @var string[] Main hours measurement of atmospheric precipitation
     */
    private $durationPeriodMap = [
        '1' => 'Total precipitation during the 6 hours preceding the observation',
        '2' => 'Total precipitation during the 12 hours preceding the observation',
        '3' => 'Total precipitation during the 18 hours preceding the observation',
        '4' => 'Total precipitation during the 24 hours preceding the observation',
        '5' => 'Total precipitation during the 1 hour preceding the observation',
        '6' => 'Total precipitation during the 2 hours preceding the observation',
        '7' => 'Total precipitation during the 3 hours preceding the observation',
        '8' => 'Total precipitation during the 9 hours preceding the observation',
        '9' => 'Total precipitation during the 15 hours preceding the observation',
        '/' => null,
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
     *
     * @param ValidateInterface $validate
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        $distinguishingDigit = substr($this->rawAmountRainfall, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [
                $this->getCodeFigureIndicator(), $this->getCodeFigureAmount(), $this->getCodeFigurePeriod()
                ]
            );
            return true;
        }

        return false;
    }

    public function getAmountRainfall(): array
    {
        /* String of Amount of rainfall */
        $RRR = substr($this->rawAmountRainfall, 1, 3);

        if (intval($RRR) >= 990 && intval($RRR) <= 999) {
            return $this->valueWithTenths($RRR);
        } else {
            return $this->integerValues($RRR);
        }
    }

    /**
     * Returns the number of duration period of Amount of rainfall
     *
     * @return string
     */
    public function getDurationPeriodNumber(): string
    {
        return substr($this->rawAmountRainfall, 4, 1);
    }

    /**
     * Returns the duration period of Amount of rainfall
     *
     * @return null|string
     * @throws Exception
     */
    public function getDurationPeriod(): ?string
    {
        $durationPeriod = substr($this->rawAmountRainfall, 4, 1);

        if (!array_key_exists($durationPeriod, $this->durationPeriodMap)) {
            throw new Exception('Wrong data of duration period of RRR');
        }

        return $this->durationPeriodMap[$durationPeriod];
    }

    /**
     * Returns the Value of Amount of rainfall
     *
     * @param string $rainfallData String of Amount of rainfall
     * @return array
     */
    public function valueWithTenths(string $rainfallData): array
    {
        if (strcasecmp($rainfallData, '990') == 0) {
            return ['Trace', null];
        }

        return [null, floatval('0.' . substr($rainfallData, 2, 1))];
    }

    /**
     * Returns the integer value of Amount of rainfall
     *
     * @param string $rainfallData String of Amount of rainfall
     * @return array - output data of format [title, value]
     */
    public function integerValues(string $rainfallData): array
    {
        if (strcasecmp($rainfallData, '000') == 0) {
            return ['There was no precipitation', null];
        }

        return [null, intval($rainfallData)];
    }

    /**
     * Returns indicator and description of amount rainfall group - 6RRRtr
     *
     * @return string[] Indicator and description of amount rainfall group
     */
    public function getIndicatorGroup(): array
    {
        return ['6' => 'Indicator'];
    }

    /**
     * Returns indicator and description of amount rainfall group - 6RRRtr
     *
     * @return string[] Indicator and description of amount rainfall
     */
    public function getAmountRainfallIndicator(): array
    {
        return ['RRR' => 'Amount of rainfall'];
    }

    /**
     * Returns indicator and description of duration period for amount rainfall group - 6RRRtr
     *
     * @return string[] Indicator and description of duration period
     */
    public function getDurationPeriodIndicator(): array
    {
        return ['tr' => 'Duration period of RRR'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getIndicatorGroup()),
            key($this->getAmountRainfallIndicator()),
            key($this->getDurationPeriodIndicator()),
        ];
    }

    /**
     * Return code figure of Amount of rainfall group
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawAmountRainfall, 0, 1);
    }

    /**
     * Return code figure of Amount of rainfall data
     *
     * @return false|string
     */
    private function getCodeFigureAmount()
    {
        return substr($this->rawAmountRainfall, 1, 3);
    }

    /**
     * Return code figure of duration period
     *
     * @return false|string
     */
    private function getCodeFigurePeriod()
    {
        return substr($this->rawAmountRainfall, 4, 1);
    }
}

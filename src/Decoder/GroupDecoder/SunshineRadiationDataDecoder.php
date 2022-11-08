<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class SunshineRadiationDataDecoder contains methods for decoding a group duration of sunshine and radiation
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SunshineRadiationDataDecoder implements GroupDecoderInterface
{
    /** Distinctive digit of duration of sunshine and radiation group */
    protected const DIGIT = '55';

    /**
     * @var string Duration of sunshine and radiation
     */
    private $rawSunshineRadiation;

    public function __construct(string $rawSunshineRadiation)
    {
        $this->rawSunshineRadiation = $rawSunshineRadiation;
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
        $distinguishingDigit = substr($this->rawSunshineRadiation, 0, 2);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup($this, [$this->getCodeFigureIndicator(), $this->getCodeSunshineData()]);
            return true;
        }

        return false;
    }

    /**
     * Returns symbolic expression for duration of daily sunshine data
     *
     * @return string Symbolic expression for duration of daily sunshine
     */
    public function getCodeSunshineData(): string
    {
        return substr($this->rawSunshineRadiation, 2, 3);
    }

    /**
     * Returns duration of daily sunshine value
     *
     * @return float Duration of daily sunshine
     */
    public function getSunshineData(): float
    {
        $SSS = $this->getCodeSunshineData();
        $integerPartString = substr($SSS, 0, 2);
        $fractionalPartString = substr($SSS, 2, 1);

        return floatval($integerPartString . '.' . $fractionalPartString);
    }

    /**
     * Returns indicator and description of duration of sunshine and radiation group - 55SSS
     *
     * @return string[] Indicator and description of duration of sunshine and radiation
     */
    public function getGetIndicatorGroup(): array
    {
        return ['55' => 'Indicator'];
    }

    /**
     * Returns indicator and description of duration of sunshine - 55SSS
     *
     * @return string[] Indicator and description of duration of sunshine
     */
    public function getDurationTinderIndicator(): array
    {
        return ['SSS' => 'Duration of daily sunshine'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getGetIndicatorGroup()),
            key($this->getDurationTinderIndicator()),
        ];
    }

    /**
     * Return code figure of duration of sunshine and radiation
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawSunshineRadiation, 0, 2);
    }
}

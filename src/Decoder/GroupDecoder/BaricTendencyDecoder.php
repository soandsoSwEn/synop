<?php


namespace Soandso\Synop\Decoder\GroupDecoder;



use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class BaricTendencyDecoder contains methods for decoding a group Pressure change over last three hours
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class BaricTendencyDecoder implements GroupDecoderInterface
{
    /** Value distinctive number of Pressure change over last three hours */
    const DIGIT = '5';

    /**
     * @var string Pressure change over last three hours data
     */
    private $rawBaricTendency;

    public function __construct(string $rawBaricTendency)
    {
        $this->rawBaricTendency = $rawBaricTendency;
    }

    /**
     * Returns the result of checking the validity of the code group
     * @param ValidateInterface $validate
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate) : bool
    {
        $distinguishingDigit = substr($this->rawBaricTendency, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(get_class($this), [$this->getCodeFigureIndicator(), $this->getCodeFigureCharacteristic(), $this->getCodeFigureChange()]);
            return true;
        }

        return false;
    }

    /**
     * Returns the Characteristic of Pressure change
     * @return int
     */
    public function getCharacteristicChange() : int
    {
        return intval(substr($this->rawBaricTendency, 1, 1));
    }

    /**
     * Returns the Pressure change over last three hours in millibars and tenths
     * @return float
     */
    public function getBaricTendency() : float
    {
        $stringOfTendency = substr($this->rawBaricTendency, 2, 3);
        $integerOfNumber = substr($stringOfTendency, 0, 2);
        $fractionalOfNumber = substr($stringOfTendency, 2, 1);
        $baricTendency = $integerOfNumber . '.' . $fractionalOfNumber;

        return floatval($baricTendency);
    }

    /**
     * Return code figure of indicator of Pressure change over last three hours
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawBaricTendency, 0, 1);
    }

    /**
     * Return code figure of Characteristic of Pressure change
     *
     * @return false|string
     */
    private function getCodeFigureCharacteristic()
    {
        return substr($this->rawBaricTendency, 1, 1);
    }

    /**
     * Return code figure of Pressure change over last three hours
     *
     * @return false|string
     */
    private function getCodeFigureChange()
    {
        return substr($this->rawBaricTendency, 2, 3);
    }
}

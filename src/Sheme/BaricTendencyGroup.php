<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\UnitInterface;
use Synop\Fabrication\ValidateInterface;


/**
 * Class BaricTendencyGroup contains methods for working with the Pressure change
 * over last three hours group - 5appp
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class BaricTendencyGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Pressure change over last three hours data
     */
    private $rawBaricTendency;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var integer Characteristic of Pressure change
     */
    private $characteristicChange;

    /**
     * @var float Pressure change over last three hours in millibars and tenths
     */
    private $tendency;

    /**
     * @var float The resulting signed Pressure change over last three hours in millibars and tenths
     */
    private $tendencyValue;

    private $downwardTrendPressure = [
        5, 6, 7, 8
    ];

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    public function setData(string $data, ValidateInterface $validate) : void
    {
        if (!empty($data)) {
            $this->rawBaricTendency = $data;
            $this->setDecoder(new BaricTendencyDecoder($this->rawBaricTendency));
            $this->setBaricTendencyGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('BaricTendencyGroup group cannot be empty!');
        }
    }

    /**
     * @param GroupDecoderInterface $decoder
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets characteristic of Pressure change
     * @param int $characteristicChange Characteristic of Pressure change
     */
    public function setCharacteristicChangeValue(int $characteristicChange) : void
    {
        $this->characteristicChange = $characteristicChange;
    }

    /**
     * Sets pressure change over last three hours in millibars and tenths
     * @param float $tendency Pressure change over last three hours in millibars and tenths
     */
    public function setTendencyValue(float $tendency) : void
    {
        $this->tendency = $tendency;
    }

    /**
     * Sets the resulting signed Pressure change over last three hours in millibars and tenths
     * @param float $tendencyValue The resulting signed Pressure change over last three hours in millibars and tenths
     */
    public function setTendencyValueData(float $tendencyValue) : void
    {
        $this->tendencyValue = $tendencyValue;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns characteristic of Pressure change
     * @return int Characteristic of Pressure change
     */
    public function getCharacteristicChangeValue() : int
    {
        return $this->characteristicChange;
    }

    /**
     * Returns pressure change over last three hours in millibars and tenths
     * @return float Pressure change over last three hours in millibars and tenths
     */
    public function getTendencyValue() : float
    {
        return $this->tendency;
    }

    /**
     * Returns the resulting signed Pressure change over last three hours in millibars and tenths
     * @return string The resulting signed Pressure change over last three hours in millibars and tenths
     */
    public function getTendencyValueData() : string
    {
        return $this->tendencyValue;
    }

    /**
     * Sets parameters of Pressure change over last three hours
     * @param GroupDecoderInterface $decoder
     * @param ValidateInterface $validate
     */
    public function setBaricTendencyGroup(GroupDecoderInterface $decoder, ValidateInterface $validate) : void
    {
        if ($this->isBaricTendencyGroup($decoder, $validate)) {
            $this->setCharacteristicChange($decoder);
            $this->setBaricTendency($decoder);
        } else {
            $this->setCharacteristicChange(null);
            $this->setBaricTendency(null);
        }
    }

    /**
     * Validates a block of code against a Pressure change over last three hours
     * @param GroupDecoderInterface $decoder
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isBaricTendencyGroup(GroupDecoderInterface $decoder, ValidateInterface $validate) : bool
    {
        return $decoder->isGroup($validate);
    }

    /**
     * Sets Characteristic of Pressure change
     * @param GroupDecoderInterface|null $decoder
     */
    public function setCharacteristicChange(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->characteristicChange = null;
        } else {
            $this->characteristicChange = $decoder->getCharacteristicChange();
        }
    }

    /**
     * Sets the value of Pressure change over last three hours in millibars and tenths
     * @param GroupDecoderInterface|null $decoder
     */
    public function setBaricTendency(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($this->characteristicChange || is_null($decoder))) {
            $this->tendency = null;
        } else {
            $this->tendency = $decoder->getBaricTendency();
            if (in_array($this->characteristicChange, $this->downwardTrendPressure)) {
                $this->tendencyValue = $this->tendency * -1;
            } else {
                $this->tendencyValue = $this->tendency;
            }
        }
    }
}
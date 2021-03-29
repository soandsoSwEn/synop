<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;


/**
 * Class BaricTendencyGroup contains methods for working with the Pressure change
 * over last three hours group - 5appp
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class BaricTendencyGroup implements GroupInterface
{
    /**
     * @var string Pressure change over last three hours data
     */
    private $rawBaricTendency;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

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

    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
    }

    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->rawBaricTendency = $data;
            $this->setDecoder(new BaricTendencyDecoder($this->rawBaricTendency));
            $this->setBaricTendencyGroup($this->getDecoder());
        } else {
            throw new Exception('BaricTendencyGroup group cannot be empty!');
        }
    }

    /**
     * Sets the value of the Unit object
     * @param UnitInterface $unit class instance of the entity Unit
     */
    public function setUnit(UnitInterface $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * Returns the value of the Unit object
     * @return UnitInterface
     */
    public function getUnit() : UnitInterface
    {
        return $this->unit;
    }

    /**
     * Returns unit data for the weather report group
     * @return array|null
     */
    public function getUnitValue() : ?array
    {
        return $this->getUnit()->getUnitByGroup(get_class($this));
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
     */
    public function setBaricTendencyGroup(GroupDecoderInterface $decoder) : void
    {
        if ($this->isBaricTendencyGroup($decoder)) {
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
     * @return bool
     */
    public function isBaricTendencyGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
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
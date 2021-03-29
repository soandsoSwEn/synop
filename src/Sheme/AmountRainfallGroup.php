<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;
use Synop\Sheme\GroupInterface;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Exception;


/**
 * Class AmountRainfallGroup contains methods for working with the amount of rainfall group - 6RRRtr
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class AmountRainfallGroup implements GroupInterface
{
    /**
     * @var string Code block of amount of rainfall
     */
    private $rawAmountRainfall;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var integer Duration period of Amount of rainfall
     */
    private $durationPeriodNumber;

    /**
     * @var string Duration period of Amount of rainfall
     */
    private $durationPeriod;

    /**
     * @var array Title and Value of Amount of rainfall in mm
     */
    private $amountRainfall;

    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for the amount of rainfall group
     * @param string $data Amount of rainfall group data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->setRawAmountRainfall($data);
            $this->setDecoder(new AmountRainfallDecoder($this->getRawAmountRainfall()));
            $this->setAmountRainfallGroup($this->getDecoder());
        } else {
            throw new Exception('AmountRainfallGroup group cannot be empty!');
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
     * Sets the parameters of Amount of rainfall group
     * @param GroupDecoderInterface $decoder
     */
    public function setAmountRainfallGroup(GroupDecoderInterface $decoder)
    {
        if ($this->isAmountRainfallGroup($decoder)) {
            $this->setAmountRainfall($decoder);
            $this->setDurationPeriodNumber($decoder);
            $this->setDurationPeriod($decoder);
        } else {
            $this->setAmountRainfall(null);
            $this->setDurationPeriodNumber(null);
            $this->setDurationPeriod(null);
        }
    }

    /**
     * Sets amount of rainfall group data
     * @param string $rawAmountRainfall Amount of rainfall group data
     */
    public function setRawAmountRainfall(string $rawAmountRainfall) : void
    {
        $this->rawAmountRainfall = $rawAmountRainfall;
    }

    /**
     * Sets an initialized decoder object for amount of rainfall group
     * @param GroupDecoderInterface $decoder Initialized decoder object for amount of rainfall group
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets duration period of Amount of rainfall integer value
     * @param int|null $durationPeriodNumber Duration period of Amount of rainfall
     */
    public function setDurationPeriodNumberValue(?int $durationPeriodNumber) : void
    {
        $this->durationPeriodNumber = $durationPeriodNumber;
    }

    /**
     * Sets duration period of Amount of rainfall string value
     * @param string|null $durationPeriod Duration period of Amount of rainfall
     */
    public function setDurationPeriodValue(?string $durationPeriod) : void
    {
        $this->durationPeriod = $durationPeriod;
    }

    /**
     * Sets title and Value of Amount of rainfall in mm
     * @param array|null $amountRainfall Title and Value of Amount of rainfall in mm
     */
    public function setAmountRainfallValue(?array $amountRainfall) : void
    {
        $this->amountRainfall = $amountRainfall;
    }

    /**
     * Returns amount of rainfall group data
     * @return string Amount of rainfall group data
     */
    public function getRawAmountRainfall() : string
    {
        return $this->rawAmountRainfall;
    }

    /**
     * Returns initialized decoder object for amount of rainfall group
     * @return GroupDecoderInterface Initialized decoder object for amount of rainfall group
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns duration period of Amount of rainfall integer value
     * @return int|null Duration period of Amount of rainfall
     */
    public function getDurationPeriodNumberValue() : ?int
    {
        return $this->durationPeriodNumber;
    }

    /**
     * Returns duration period of rainfall string value
     * @return string|null Duration period of Amount of rainfall
     */
    public function getDurationPeriodValue() : ?string
    {
        return $this->durationPeriod;
    }

    /**
     * Returns title and Value of Amount of rainfall in mm
     * @return array|null Title and Value of Amount of rainfall in mm
     */
    public function getAmountRainfallValue() : ?array
    {
        return $this->amountRainfall;
    }

    /**
     * Validates a block of code against a Amount of rainfall
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isAmountRainfallGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the value of Amount of rainfall
     * @param GroupDecoderInterface|null $decoder
     */
    public function setAmountRainfall(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setAmountRainfallValue(null);
        } else {
            $this->setAmountRainfallValue($decoder->getAmountRainfall());
        }
    }

    /**
     * Sets the value of duration period number of of Amount of rainfall
     * @param GroupDecoderInterface|null $decoder
     */
    public function setDurationPeriodNumber(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setDurationPeriodNumberValue(null);
        } else {
            $this->setDurationPeriodNumberValue($decoder->getDurationPeriodNumber());
        }
    }

    /**
     * Sets the value of duration period of of Amount of rainfall
     * @param GroupDecoderInterface|null $decoder
     */
    public function setDurationPeriod(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setDurationPeriodValue(null);
        } else {
            $this->setDurationPeriodValue($decoder->getDurationPeriod());
        }
    }
}
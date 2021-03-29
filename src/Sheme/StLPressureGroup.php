<?php


namespace Synop\Sheme;

use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;
use Synop\Sheme\GroupInterface;
use Exception;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\StLPressureDecoder;


/**
 * Class StLPressureGroup contains methods for working with the atmospheric pressure group
 * at the station level - 3P0P0P0P0
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class StLPressureGroup implements GroupInterface
{
    /**
     * @var string Station level atmospheric pressure
     */
    private $raw_stl_pressure;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var float Value atmospheric pressure at station level
     */
    private $pressure;

    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
    }

    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->raw_stl_pressure = $data;
            $this->setDecoder(new StLPressureDecoder($this->raw_stl_pressure));
            $this->setStLPressureGroup($this->getDecoder());
        } else {
            throw new Exception('StLPressureGroup group cannot be empty!');
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
     * Sets value atmospheric pressure at station level
     * @param float $pressure Value atmospheric pressure at station level
     */
    public function setPressureValue(float $pressure) : void
    {
        $this->pressure = $pressure;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns value atmospheric pressure at station level
     * @return float Value atmospheric pressure at station level
     */
    public function getPressureValue() : float
    {
        return $this->pressure;
    }

    /**
     * Sets the parameters of the Station level atmospheric pressure group
     * @param GroupDecoderInterface $decoder
     */
    public function setStLPressureGroup(GroupDecoderInterface $decoder)
    {
        if ($this->isStLPressureGroup($decoder)) {
            $this->setStLPressure($decoder);
        } else {
            $this->setStLPressure(null);
        }
    }

    /**
     * validates a block of code against a Station level atmospheric pressure group
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isStLPressureGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the Station level atmospheric pressure
     * @param GroupDecoderInterface|null $decoder
     */
    public function setStLPressure(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->pressure = null;
        } else {
            $this->pressure = $decoder->getStLPressure();
        }
    }
}
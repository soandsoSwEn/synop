<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class MslPressureGroup contains methods for working with the atmospheric pressure group
 * at mean sea level - 4PPPP
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class MslPressureGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Code block of Air Pressure reduced to mean sea level
     */
    private $rawMlsPressure;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var float Value of Air Pressure reduced to mean sea level
     */
    private $pressure;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for atmospheric pressure group
     *
     * @param string $data Code block of Air Pressure reduced to mean sea level
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawMlsPressure = $data;
            $this->setDecoder(new MslPressureDecoder($this->rawMlsPressure));
            $this->setMslPressureGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('MslPressureGroup group cannot be empty!');
        }
    }

    /**
     * Sets an initialized decoder object for atmospheric pressure group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets value of Air Pressure reduced to mean sea level
     *
     * @param float $pressure Value of Air Pressure reduced to mean sea level
     */
    public function setPressureValue(float $pressure): void
    {
        $this->pressure = $pressure;
    }

    /**
     * Returns an initialized decoder object for atmospheric pressure group
     *
     * @return GroupDecoderInterface Initialized decoder object
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns value of Air Pressure reduced to mean sea level
     *
     * @return float Value of Air Pressure reduced to mean sea level
     */
    public function getPressureValue(): float
    {
        return $this->pressure;
    }

    /**
     * Sets the parameters of Air Pressure reduced to mean sea level
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     */
    public function setMslPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): void
    {
        if ($this->isMslPressureGroup($decoder, $validate)) {
            $this->setMslPressure($decoder);
        } else {
            $this->setMslPressure(null);
        }
    }

    /**
     * Validates a block of code against a Air Pressure reduced to mean sea level group
     *
     * @param GroupDecoderInterface $decoder
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isMslPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate, $this->getGroupIndicator());
    }

    /**
     * Sets the value of Air Pressure reduced to mean sea level
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setMslPressure(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->pressure = null;
        } else {
            $this->pressure = $decoder->getMslPressure();
        }
    }

    /**
     * Returns the indicator of the entire weather report group
     *
     * @return string Group indicator
     */
    public function getGroupIndicator(): string
    {
        return '4PPPP';
    }
}

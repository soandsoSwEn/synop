<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Fabrication\UnitInterface;
use Exception;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class StLPressureGroup contains methods for working with the atmospheric pressure group
 * at the station level - 3P0P0P0P0
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class StLPressureGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Station level atmospheric pressure
     */
    private $rawStlPressure;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var float|null Value atmospheric pressure at station level
     */
    private ?float $pressure;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for atmospheric pressure group
     *
     * @param string $data Code figure of station level atmospheric pressure group
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawStlPressure = $data;
            $this->setDecoder(new StLPressureDecoder($this->rawStlPressure));
            $this->setStLPressureGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('StLPressureGroup group cannot be empty!');
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
     * Sets value atmospheric pressure at station level
     *
     * @param float $pressure Value atmospheric pressure at station level
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
     * Returns value atmospheric pressure at station level
     *
     * @return float|null Value atmospheric pressure at station level
     */
    public function getPressureValue(): ?float
    {
        return $this->pressure;
    }

    /**
     * Sets the parameters of the Station level atmospheric pressure group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     */
    public function setStLPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isStLPressureGroup($decoder, $validate)) {
            $this->setStLPressure($decoder);
        } else {
            $this->setStLPressure(null);
        }
    }

    /**
     * Validates a block of code against a Station level atmospheric pressure group
     *
     * @param GroupDecoderInterface $decoder
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isStLPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate, $this->getGroupIndicator());
    }

    /**
     * Sets the Station level atmospheric pressure
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setStLPressure(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->pressure = null;
        } else {
            $this->pressure = $decoder->getStLPressure();
        }
    }

    /**
     * Returns the indicator of the entire weather report group
     *
     * @return string Group indicator
     */
    public function getGroupIndicator(): string
    {
        return '3P0P0P0P0';
    }
}

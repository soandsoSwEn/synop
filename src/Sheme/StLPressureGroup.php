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
    private $raw_stl_pressure;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var float Value atmospheric pressure at station level
     */
    private $pressure;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    public function setData(string $data, ValidateInterface $validate) : void
    {
        if (!empty($data)) {
            $this->raw_stl_pressure = $data;
            $this->setDecoder(new StLPressureDecoder($this->raw_stl_pressure));
            $this->setStLPressureGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('StLPressureGroup group cannot be empty!');
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
    public function setStLPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isStLPressureGroup($decoder, $validate)) {
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
    public function isStLPressureGroup(GroupDecoderInterface $decoder, ValidateInterface $validate) : bool
    {
        return $decoder->isGroup($validate);
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
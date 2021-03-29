<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\UnitInterface;
use Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Exception;


/**
 * Class MslPressureGroup contains methods for working with the atmospheric pressure group
 * at mean sea level level - 4P0P0P0P0
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
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var float Value of Air Pressure reduced to mean sea level
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
            $this->rawMlsPressure = $data;
            $this->setDecoder(new MslPressureDecoder($this->rawMlsPressure));
            $this->setMslPressureGroup($this->getDecoder());
        } else {
            throw new Exception('MslPressureGroup group cannot be empty!');
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
     * Sets value of Air Pressure reduced to mean sea level
     * @param float $pressure Value of Air Pressure reduced to mean sea level
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
     * Returns value of Air Pressure reduced to mean sea level
     * @return float Value of Air Pressure reduced to mean sea level
     */
    public function getPressureValue() : float
    {
        return $this->pressure;
    }

    /**
     * Sets the parameters of Air Pressure reduced to mean sea level
     * @param GroupDecoderInterface $decoder
     */
    public function setMslPressureGroup(GroupDecoderInterface $decoder) : void
    {
        if ($this->isMslPressureGroup($decoder)) {
            $this->setMslPressure($decoder);
        } else {
            $this->setMslPressure(null);
        }
    }

    /**
     * Validates a block of code against a Air Pressure reduced to mean sea level group
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isMslPressureGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the value of Air Pressure reduced to mean sea level
     * @param GroupDecoderInterface|null $decoder
     */
    public function setMslPressure(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->pressure = null;
        } else {
            $this->pressure = $decoder->getMslPressure();
        }
    }
}
<?php


namespace Synop\Sheme;

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
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var float Value atmospheric pressure at station level
     */
    private $pressure;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    public function setData(string $data)
    {
        if (!empty($data)) {
            $this->raw_stl_pressure = $data;
            $this->decoder = new StLPressureDecoder($this->raw_stl_pressure);
            $this->setStLPressureGroup($this->decoder);
        } else {
            throw new Exception('StLPressureGroup group cannot be empty!');
        }
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
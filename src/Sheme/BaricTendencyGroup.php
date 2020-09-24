<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;


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

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->rawBaricTendency = $data;
            $this->decoder = new BaricTendencyDecoder($this->rawBaricTendency);
            $this->setBaricTendencyGroup($this->decoder);
        } else {
            throw new Exception('BaricTendencyGroup group cannot be empty!');
        }
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
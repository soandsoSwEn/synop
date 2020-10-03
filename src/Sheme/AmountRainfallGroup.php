<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
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

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    public function setData(string $data)
    {
        if (!empty($data)) {
            $this->rawAmountRainfall = $data;
            $this->decoder = new AmountRainfallDecoder($this->rawAmountRainfall);
            $this->setAmountRainfallGroup($this->decoder);
        } else {
            throw new Exception('AmountRainfallGroup group cannot be empty!');
        }
    }

    /**
     * Sets the parameters of Amount of rainfall
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
            $this->amountRainfall = null;
        } else {
            $this->amountRainfall = $decoder->getAmountRainfall();
        }
    }

    /**
     * Sets the value of duration period number of of Amount of rainfall
     * @param GroupDecoderInterface|null $decoder
     */
    public function setDurationPeriodNumber(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->durationPeriodNumber = null;
        } else {
            $this->durationPeriodNumber = $decoder->getDurationPeriodNumber();
        }
    }

    /**
     * Sets the value of duration period of of Amount of rainfall
     * @param GroupDecoderInterface|null $decoder
     */
    public function setDurationPeriod(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->durationPeriod = null;
        } else {
            $this->durationPeriod = $decoder->getDurationPeriod();
        }
    }
}
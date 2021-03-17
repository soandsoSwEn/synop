<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Sheme\GroupInterface;
use Exception;
use Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;


/**
 * Class DewPointTemperatureGroup contains methods for working with the dew point temperature group - 2SnTdTdTd
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DewPointTemperatureGroup implements GroupInterface
{
    /**
     * @var string Dew point temperature group data
     */
    private $raw_dp_temperature;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var int Dew point temperature sign
     */
    private $sign;

    /**
     * @var float Dew point temperature in tenths
     */
    private $dewPoint;

    /**
     * @var float The resulting signed dew point temperature
     */
    private $dewPointValue;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    public function setData(string $data)
    {
        if (!empty($data)) {
            $this->raw_dp_temperature = $data;
            $this->decoder = new DewPointTemperatureDecoder($this->raw_dp_temperature);
            $this->setDwPtTemperatureGroup($this->decoder);
        } else {
            throw new Exception('DewPointTemperatureGroup group cannot be empty!');
        }
    }

    /**
     * Sets dew point temperature sign value
     * @param int $sign Dew point temperature sign
     */
    public function setSignValue(int $sign) : void
    {
        $this->sign = $sign;
    }

    /**
     * Sets dew point temperature in tenths value
     * @param float $dewPointTemperature Dew point temperature in tenths
     */
    public function setDewPointValue(float $dewPointTemperature) : void
    {
        $this->dewPoint = $dewPointTemperature;
    }

    /**
     * Sets The resulting signed dew point temperature value
     * @param float $resultDewPointTemperature Resulting signed dew point temperature
     */
    public function setResultDewPointValue(float $resultDewPointTemperature) : void
    {
        $this->dewPointValue = $resultDewPointTemperature;
    }

    /**
     * Returns dew point temperature sign
     * @return int Dew point temperature sign
     */
    public function getSignValue() : int
    {
        return $this->sign;
    }

    /**
     * Returns dew point temperature in tenths
     * @return float Dew point temperature in tenths
     */
    public function getDewPointValue() : float
    {
        return $this->dewPoint;
    }

    /**
     * Returns resulting signed dew point temperature
     * @return float Resulting signed dew point temperature
     */
    public function getResultDewPointValue() : float
    {
        return $this->dewPointValue;
    }

    /**
     * Sets the parameters of the dew point temperature group
     * @param GroupDecoderInterface $decoder
     */
    public function setDwPtTemperatureGroup(GroupDecoderInterface $decoder) : void
    {
        if ($this->isDwPtTemperatureGroup($decoder)) {
            $this->setSign($decoder);
            $this->setDewPointTemperature($decoder);
            $this->buildDewPointTemperature($this->sign, $this->dewPoint);
        } else {
            $this->setSign(null);
            $this->setDewPointTemperature(null);
            $this->buildDewPointTemperature($this->sign, $this->dewPoint);
        }
    }

    /**
     * Validates a block of code against a dew point temperature group
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isDwPtTemperatureGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the sign of the dew point temperature
     * @param GroupDecoderInterface|null $decoder
     */
    public function setSign(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->sign = null;
        } else {
            $this->sign = $decoder->getSignDewPointTemperature();
        }
    }

    /**
     * Sets the dew point temperature
     * @param GroupDecoderInterface|null $decoder
     */
    public function setDewPointTemperature(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->dewPoint = null;
        } else {
            $this->dewPoint = $decoder->getDewPointTemperature();
        }
    }

    /**
     * Sets the full value of the signed dew point temperature
     * @param int|null $sign Dew point temperature sign
     * @param float|null $dewPoint Dew point temperature in tenths
     */
    public function buildDewPointTemperature(?int $sign, ?float $dewPoint) : void
    {
        if (!is_null($sign) && !is_null($dewPoint)) {
            if ($sign == 1) {
                $this->dewPointValue = $dewPoint * -1;
            } else {
                $this->dewPointValue = $dewPoint;
            }
        } else {
            $this->dewPointValue = null;
        }
    }
}
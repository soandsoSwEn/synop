<?php


namespace Synop\Sheme;


use Exception;
use Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;

/**
 * Class GroundWithoutSnowGroup contains methods for working with a group of state of the ground without snow
 * or measurable ice cover of section three - 333 3ESnTgTg
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GroundWithoutSnowGroup implements GroupInterface
{
    /**
     * @var string State of ground without snow or measurable ice cover group data
     */
    private $raw_ground_without_snow;

    /**
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var int Code figure of state ground
     */
    private $codeState;

    /**
     * @var string State ground
     */
    private $state;

    /**
     * @var int Sign of temperature
     */
    private $sign;

    /**
     * @var int Grass minimum temperature (rounded to nearest whole degree)
     */
    private $minTemperature;

    /**
     * @var int The resulting signed grass minimum temperature
     */
    private $minTemperatureValue;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * Sets the initial data for state of ground group
     * @param string $data state of ground group data
     * @throws Exception
     */
    public function setData(string $data)
    {
        if (!empty($data)) {
            $this->setRawGroundWithoutSnow($data);
            $this->setDecoder(new GroundWithoutSnowDecoder($this->getRawGroundWithoutSnow()));
            $this->setGroundWithoutSnowGroup($this->getDecoder());
        } else {
            throw new Exception('GroundWithoutSnowGroup group cannot be empty!');
        }
    }

    /**
     * Sets state of ground group data
     * @param string $data
     */
    public function setRawGroundWithoutSnow(string $data) : void
    {
        $this->raw_ground_without_snow = $data;
    }

    /**
     * Sets an initialized decoder object for state of ground group
     * @param GroupDecoderInterface $decoder
     */
    public function setDecoder(GroupDecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets code figure of state ground group
     * @param int|null $codeState Code figure of state ground
     */
    public function setCodeStateValue(?int $codeState)
    {
        $this->codeState = $codeState;
    }

    /**
     * Sets state ground value
     * @param string|null $state State ground
     */
    public function setStateValue(?string $state)
    {
        $this->state = $state;
    }

    /**
     * Sets grass minimum temperature sign
     * @param int|null $sign Grass minimum temperature sign
     */
    public function setSignValue(?int $sign)
    {
        $this->sign = $sign;
    }

    /**
     * Sets grass minimum temperature
     * @param int|null $minTemperature Grass minimum temperature (rounded to nearest whole degree)
     */
    public function setMinTemperatureValue(?int $minTemperature)
    {
        $this->minTemperature = $minTemperature;
    }

    /**
     * Sets resulting signed grass minimum temperature
     * @param int|null $resultMinTemperature resulting signed grass minimum temperature (rounded to nearest whole degree)
     */
    public function setResultMinTemperature(?int $resultMinTemperature)
    {
        $this->minTemperatureValue = $resultMinTemperature;
    }

    /**
     * Returns state of ground without snow or measurable ice cover group data
     * @return string State of ground without snow or measurable ice cover group
     */
    public function getRawGroundWithoutSnow() : string
    {
        return $this->raw_ground_without_snow;
    }

    /**
     * Returns initialized decoder object for state of ground group
     * @return GroupDecoderInterface Initialized decoder object for state of ground group
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns code figure of state ground group
     * @return int|null
     */
    public function getCodeStateValue() : ?int
    {
        return $this->codeState;
    }

    /**
     * Return state ground value
     * @return string|null
     */
    public function getStateValue() : ?string
    {
        return $this->state;
    }

    /**
     * Returns grass minimum temperature sign
     * @return int|null
     */
    public function getSignValue() : ?int
    {
        return $this->sign;
    }

    /**
     * Returns grass minimum temperature
     * @return int|null Grass minimum temperature (rounded to nearest whole degree)
     */
    public function getMinTemperatureValue() : ?int
    {
        return $this->minTemperature;
    }

    /**
     * Returns resulting signed grass minimum temperature
     * @return int|null resulting signed grass minimum temperature (rounded to nearest whole degree)
     */
    public function getResultMinTemperature() : ?int
    {
        return $this->minTemperatureValue;
    }

    /**
     * Sets values for the state of the ground group variables
     * @param GroupDecoderInterface $decoder Initialized decoder object for state of ground group
     */
    public function setGroundWithoutSnowGroup(GroupDecoderInterface $decoder)
    {
        if ($this->isDrWtSnGroup($decoder)) {
            $this->setCodeState($decoder);
            $this->setState($decoder);
            $this->setSign($decoder);
            $this->setMinTemperature($decoder);
            $this->buildMinTemperature($this->getSignValue(), $this->getMinTemperatureValue());
        } else {
            $this->setCodeState(null);
            $this->setState(null);
            $this->setSign(null);
            $this->setMinTemperature(null);
            $this->buildMinTemperature(null, null);
        }
    }

    /**
     * Returns whether the given group is a state of ground group
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @return bool
     */
    public function isDrWtSnGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets Code figure of state ground
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setCodeState(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setCodeStateValue(null);
        } else {
            $this->setCodeStateValue($decoder->getCodeGroundState());
        }
    }

    /**
     * Sets state ground
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setState(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setStateValue(null);
        } else {
            $this->setStateValue($decoder->getGroundState());
        }
    }

    /**
     * Sets the grass minimum temperature sign value
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setSign(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setSignValue(null);
        } else {
            $this->setSignValue($decoder->getGroundSignTemperature());
        }
    }

    /**
     * Sets the grass minimum temperature value
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setMinTemperature(?GroupDecoderInterface $decoder)
    {
        if (is_null($decoder)) {
            $this->setMinTemperatureValue(null);
        } else {
            $this->setMinTemperatureValue($decoder->getGroundMinTemperature());
        }
    }

    /**
     * Sets the full value of the signed grass minimum temperature
     * @param int|null $sign Sign of temperature
     * @param int|null $temperature Grass minimum temperature (rounded to nearest whole degree)
     */
    public function buildMinTemperature(?int $sign, ?int $temperature)
    {
        if (!is_null($sign) && !is_null($temperature)) {
            if ($sign == 1) {
                $resultTemperature = $temperature * -1;
                $this->setResultMinTemperature($resultTemperature);
            } else {
                $this->setResultMinTemperature($temperature);
            }
        } else {
            $this->setResultMinTemperature(null);
        }
    }
}
<?php


namespace Soandso\Synop\Sheme;


use Exception;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class GroundWithSnowGroup contains methods for working with a group of state of the ground with snow
 * or measurable ice cover of section three - 333 4Esss
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GroundWithSnowGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string State of ground with snow or measurable ice cover group data
     */
    private $raw_ground_with_snow;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var int Code figure of state of ground
     */
    private $codeState;

    /**
     * @var string State of ground title
     */
    private $state;

    /**
     * @var array Depth of snow
     */
    private $depthSnow;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for state of ground group
     * @param string $data state of ground with snow or measurable ice cover group data
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate) : void
    {
        if (!empty($data)) {
            $this->setRawGroundWithSnow($data);
            $this->setDecoder(new GroundWithSnowDecoder($this->getRawGroundWithSnow()));
            $this->setGroundWithSnowGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('GroundWithSnowGroup group cannot be empty!');
        }
    }

    /**
     * Sets state of ground with snow or measurable ice cover group data
     * @param string $data State of ground with snow or measurable ice cover
     */
    public function setRawGroundWithSnow(string $data) : void
    {
        $this->raw_ground_with_snow = $data;
    }

    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets code figure of state ground with snow group
     * @param int|null $codeState Code figure of state ground with snow
     */
    public function setCodeStateValue(?int $codeState) : void
    {
        $this->codeState = $codeState;
    }

    /**
     * Sets state ground with snow value
     * @param string|null $state State ground with snow
     */
    public function setStateValue(?string $state) : void
    {
        $this->state = $state;
    }

    /**
     * Sets depth of snow value
     * @param array|null $depthSnow Depth of snow
     */
    public function setDepthSnowValue(?array $depthSnow) : void
    {
        $this->depthSnow = $depthSnow;
    }

    /**
     * Returns state of ground with snow or measurable ice cover group data
     * @return string State of ground with snow or measurable ice cover group
     */
    public function getRawGroundWithSnow() : string
    {
        return $this->raw_ground_with_snow;
    }

    /**
     * Returns initialized decoder object for state of ground with snow group
     * @return GroupDecoderInterface Decoder object for state of ground with snow group
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns code figure of state ground with snow group
     * @return int|null Code figure of state ground with snow group
     */
    public function getCodeStateValue() : ?int
    {
        return $this->codeState;
    }

    /**
     * Returns state ground with snow value
     * @return string|null State ground with snow
     */
    public function getStateValue() : ?string
    {
        return $this->state;
    }

    /**
     * Returns depth of snow value
     * @return array|null Depth of snow
     */
    public function getDepthSnowValue() : ?array
    {
        return $this->depthSnow;
    }

    /**
     * Returns whether the given group is a state of ground with snow group
     * @param GroupDecoderInterface $decoder Initialized decoder object for state of ground with snow group
     */
    public function setGroundWithSnowGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isDrWthSnGroup($decoder, $validate)) {
            $this->setCodeState($decoder);
            $this->setState($decoder);
            $this->setDepthSnow($decoder);
        } else {
            $this->setCodeState(null);
            $this->setState(null);
            $this->setDepthSnow(null);
        }
    }

    /**
     * Returns whether the given group is a state of ground with snow group
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isDrWthSnGroup(GroupDecoderInterface $decoder, ValidateInterface $validate) : bool
    {
        return $decoder->isGroup($validate);
    }

    /**
     * Sets Code figure of state ground with snow
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
     * Sets state ground with snow
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
     * Sets depth of snow
     * @param GroupDecoderInterface|null $decoder Initialized decoder object
     */
    public function setDepthSnow(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setDepthSnowValue(null);
        } else {
            $this->setDepthSnowValue($decoder->getDepthSnow());
        }
    }
}

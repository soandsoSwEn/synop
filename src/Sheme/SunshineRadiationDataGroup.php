<?php


namespace Soandso\Synop\Sheme;


use Exception;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class SunshineRadiationDataGroup contains methods for working with a duration of sunshine and radiation group
 * of section three - 333 55SSS
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SunshineRadiationDataGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Duration of sunshine and radiation group data
     */
    private $rawSunshineRadiation;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var string Symbolic expression for duration of daily sunshine
     */
    private $codeSunshine;

    /**
     * @var float Duration of daily sunshine
     */
    private $sunshine;

    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for duration of sunshine and radiation group
     * @param string $data Duration of sunshine and radiation group data
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate) : void
    {
        if (!empty($data)) {
            $this->setRawSunshineRadiation($data);
            $this->setDecoder(new SunshineRadiationDataDecoder($this->getRawSunshineRadiation()));
            $this->setSunshineRadiationGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('SunshineRadiationDataGroup group cannot be empty!');
        }
    }

    /**
     * Sets duration of sunshine and radiation group data
     * @param string $data Duration of sunshine and radiation group data
     */
    public function setRawSunshineRadiation(string $data) : void
    {
        $this->rawSunshineRadiation = $data;
    }

    /**
     * Sets an initialized decoder object for duration of sunshine and radiation group
     * @param GroupDecoderInterface $decoder Decoder object for duration of sunshine and radiation group
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets symbolic expression for duration of daily sunshine value
     * @param string|null $codeSunshine Symbolic expression for duration of daily sunshine value
     */
    public function setCodeSunshineValue(?string $codeSunshine) : void
    {
        $this->codeSunshine = $codeSunshine;
    }

    /**
     * Sets duration of daily sunshine value
     * @param float|null $sunshine Duration of daily sunshine value
     */
    public function setSunshineValue(?float $sunshine) : void
    {
        $this->sunshine = $sunshine;
    }

    /**
     * Returns duration of sunshine and radiation group data
     * @return string Duration of sunshine and radiation group data
     */
    public function getRawSunshineRadiation() : string
    {
        return $this->rawSunshineRadiation;
    }

    /**
     * Returns decoder object for duration of sunshine and radiation group
     * @return GroupDecoderInterface Decoder object for duration of sunshine and radiation group
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns symbolic expression for duration of daily sunshine value
     * @return string Symbolic expression for duration of daily sunshine value
     */
    public function getCodeSunshineValue() : ?string
    {
        return $this->codeSunshine;
    }

    /**
     * Returns duration of daily sunshine value
     * @return float|null Duration of daily sunshine value
     */
    public function getSunshineValue() : ?float
    {
        return $this->sunshine;
    }

    /**
     * Sets values for the state of the duration of sunshine and radiation group variables
     * @param GroupDecoderInterface $decoder Decoder object for duration of sunshine and radiation group
     */
    public function setSunshineRadiationGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isSunshineRadiationGroup($decoder, $validate)) {
            $this->setCodeSunshine($decoder);
            $this->setSunshine($decoder);
        } else {
            $this->setCodeSunshine(null);
            $this->setSunshine(null);
        }
    }

    /**
     * Returns whether the given group is the duration of sunshine and radiation group
     * @param GroupDecoderInterface $decoder Decoder object for duration of sunshine and radiation group
     * @param ValidateInterface $validate
     * @return bool
     */
    public function isSunshineRadiationGroup(GroupDecoderInterface $decoder, ValidateInterface $validate) : bool
    {
        return $decoder->isGroup($validate);
    }

    /**
     * Sets symbolic expression for duration of daily sunshine
     * @param GroupDecoderInterface|null $decoder Decoder object for duration of sunshine and radiation group
     */
    public function setCodeSunshine(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->setCodeSunshineValue(null);
        } else {
            $this->setCodeSunshineValue($decoder->getCodeSunshineData());
        }
    }

    /**
     * Sets duration of daily sunshine
     * @param GroupDecoderInterface|null $decoder Decoder object for duration of sunshine and radiation group
     */
    public function setSunshine(?GroupDecoderInterface $decoder)
    {
        if (is_null($decoder)) {
            $this->setSunshineValue(null);
        } else {
            $this->setSunshineValue($decoder->getSunshineData());
        }
    }
}

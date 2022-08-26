<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * The LowCloudVisibilityGroup class contains methods for working with
 * a group of cloud height and horizontal visibility - irixhVV
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class LowCloudVisibilityGroup extends BaseGroupWithUnits implements GroupInterface
{
    /**
     * @var string Code figure of Cloud height and horizontal visibility group
     */
    private $rawCloudVis;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;
    
    /**
     * @var string|null index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr
     */
    private $incPrecipGroup;
    
    /**
     * @var array|null weather indicator inclusion index 7wwW1W2
     */
    private $incWeatherGroup;
    
    /**
     * @var string|null base height of low clouds above sea level
     */
    private $heightLowClouds;
    
    /**
     * @var string|null meteorological range of visibility
     */
    private $visibility;


    public function __construct(string $data, UnitInterface $unit, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
        $this->setUnit($unit);
    }

    /**
     * Sets the initial data for Cloud height and horizontal visibility group
     *
     * @param string $data Code figure of cloud height and horizontal visibility group
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawCloudVis = $data;
            $this->setDecoder(new LowCloudVisibilityDecoder($this->rawCloudVis));
            $this->setLcvGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('LowCloudVisibility group cannot be empty!');
        }
    }

    /**
     * Sets an initialized decoder object for Cloud height and horizontal visibility group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets index of the point of inclusion in the metrological report of precipitation group
     *
     * @param string|null $incPrecipGroup index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr
     *
     * @return void
     */
    public function setIncPrecipValue(?string $incPrecipGroup): void
    {
        $this->incPrecipGroup = $incPrecipGroup;
    }

    /**
     * Sets weather indicator inclusion index 7wwW1W2
     *
     * @param array|null $incWeatherGroup Weather indicator inclusion index 7wwW1W2
     * @return void
     */
    public function setIncWeatherValue(?array $incWeatherGroup): void
    {
        $this->incWeatherGroup = $incWeatherGroup;
    }

    /**
     * Sets base height of low clouds above sea level
     *
     * @param string|null $heightLowClouds Base height of low clouds above sea level
     * @return void
     */
    public function setHeightLowValue(?string $heightLowClouds): void
    {
        $this->heightLowClouds = $heightLowClouds;
    }

    //TODO string or integer value
    /**
     * Sets meteorological range of visibility
     *
     * @param string|null $visibility Meteorological range of visibility
     * @return void
     */
    public function setVisibilityValue(?string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * Returns initialized decoder object for Cloud height and horizontal visibility group
     *
     * @return GroupDecoderInterface Initialized decoder object
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Sets index of the point of inclusion in the metrological report of precipitation group
     *
     * @return string
     */
    public function getIncPrecipValue(): string
    {
        return $this->incPrecipGroup;
    }

    /**
     * Returns weather indicator inclusion index 7wwW1W2
     *
     * @return array
     */
    public function getIncWeatherValue(): array
    {
        return $this->incWeatherGroup;
    }

    /**
     * Returns base height of low clouds above sea level
     *
     * @return string
     */
    public function getHeightLowValue(): string
    {
        return $this->heightLowClouds;
    }

    /**
     * Sets meteorological range of visibility
     *
     * @return string
     */
    public function getVisibilityValue(): string
    {
        return $this->visibility;
    }

    /**
     * Sets the parameters of a group of cloud height and horizontal visibility
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     */
    public function setLcvGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): void
    {
        if ($decoder->isGroup($validate)) {
            $this->setIncPrecip($decoder);
            $this->setIncWeather($decoder);
            $this->setHlowClouds($decoder);
            $this->setVisibility($decoder);
        } else {
            $this->setIncPrecip(null);
            $this->setIncWeather(null);
            $this->setHlowClouds(null);
            $this->setVisibility(null);
        }
    }

    /**
     * Sets value of index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr
     *
     * @param GroupDecoderInterface|null $decoder
     * @return void
     */
    public function setIncPrecip(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setIncPrecipValue(null);
        } else {
            $this->setIncPrecipValue($decoder->getIr());
        }
    }

    /**
     * Sets value of weather indicator inclusion index 7wwW1W2
     *
     * @param GroupDecoderInterface|null $decoder
     * @return void
     */
    public function setIncWeather(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setIncWeatherValue(null);
        } else {
            $this->setIncWeatherValue($decoder->getIx());
        }
    }

    /**
     * Sets base height of low clouds above sea level value
     *
     * @param GroupDecoderInterface|null $decoder
     * @return void
     */
    public function setHlowClouds(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setHeightLowValue(null);
        } else {
            $this->setHeightLowValue($decoder->getH());
        }
    }

    /**
     * Sets meteorological range of visibility value
     *
     * @param GroupDecoderInterface|null $decoder
     * @return void
     */
    public function setVisibility(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setVisibilityValue(null);
        } else {
            $this->setVisibilityValue($decoder->getVV());
        }
    }
}

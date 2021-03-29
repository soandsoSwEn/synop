<?php

namespace Synop\Sheme;

use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;
use Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Exception;

/**
 * The LowCloudVisibilityGroup class contains methods for working with 
 * a group of cloud height and horizontal visibility - 'irixhVV'
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class LowCloudVisibilityGroup implements GroupInterface
{
    private $raw_cloud_vis;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;
    
    private $decoder;
    
    /** 
     * @var string index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr 
     */
    private $inc_precip_group;
    
    /**
     * @var array weather indicator inclusion index 7wwW1W2
     */
    private $inc_weather_group;
    
    /**
     * @var string base height of low clouds above sea level 
     */
    private $height_low_clouds;
    
    /**
     * @var string meteorological range of visibility
     */
    private $visibility;


    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
    }
    
    public function setData(string $data) : void
    {
        if(!empty($data)) {
            $this->raw_cloud_vis = $data;
            $this->setDecoder(new LowCloudVisibilityDecoder($this->raw_cloud_vis));
            $this->setLcvGroup($this->getDecoder());
        } else {
            throw new Exception('LowCloudVisibility group cannot be empty!');
        }
    }

    /**
     * Sets the value of the Unit object
     * @param UnitInterface $unit class instance of the entity Unit
     */
    public function setUnit(UnitInterface $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * Returns the value of the Unit object
     * @return UnitInterface
     */
    public function getUnit() : UnitInterface
    {
        return $this->unit;
    }

    /**
     * Returns unit data for the weather report group
     * @return array|null
     */
    public function getUnitValue() : ?array
    {
        return $this->getUnit()->getUnitByGroup(get_class($this));
    }

    /**
     * @param GroupDecoderInterface $decoder
     */
    public function setDecoder(GroupDecoderInterface $decoder) : void
    {
        $this->decoder = $decoder;
    }

    public function setIncPrecipValue(string $incPrecipGroup) : void
    {
        $this->inc_precip_group = $incPrecipGroup;
    }

    public function setIncWeatherValue(array $incWeatherGroup) : void
    {
        $this->inc_weather_group = $incWeatherGroup;
    }

    public function setHeightLowValue(string $heightLowClouds) : void
    {
        $this->height_low_clouds = $heightLowClouds;
    }

    public function setVisibilityValue(string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    public function getIncPrecipValue() : string
    {
        return $this->inc_precip_group;
    }

    public function getIncWeatherValue() : array
    {
        return $this->inc_weather_group;
    }

    public function getHeightLowValue() : string
    {
        return $this->height_low_clouds;
    }

    public function getVisibilityValue() : string
    {
        return $this->visibility;
    }
    
    /**
     * Sets the parameters of a group of cloud height and horizontal visibility
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setLcvGroup(GroupDecoderInterface $decoder) : void
    {
        $this->setIncPrecip($decoder);
        $this->setIncWeather($decoder);
        $this->setHlowClouds($decoder);
        $this->setVisibility($decoder);
    }
    
    /**
     * Sets index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setIncPrecip(GroupDecoderInterface $decoder) : void
    {
        $this->setIncPrecipValue($decoder->getIr());
    }
    
    /**
     * Sets weather indicator inclusion index 7wwW1W2
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setIncWeather(GroupDecoderInterface $decoder) : void
    {
        $this->setIncWeatherValue($decoder->getIx());
    }
    
    /**
     * Sets base height of low clouds above sea level
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setHlowClouds(GroupDecoderInterface $decoder) : void
    {
        $this->setHeightLowValue($decoder->getH());
    }
    
    /**
     * Sets meteorological range of visibility
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setVisibility(GroupDecoderInterface $decoder) : void
    {
        $this->setVisibilityValue($decoder->getVV());
    }
}

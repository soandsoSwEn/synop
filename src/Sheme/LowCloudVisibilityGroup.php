<?php

namespace Synop\Sheme;

use Synop\Sheme\GroupInterface;
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
    
    private $decoder;
    
    /** 
     * @var string index of the point of inclusion in the metrological report
     * of the precipitation group 6RRRtr 
     */
    private $inc_precip_group;
    
    /**
     * @var string weather indicator inclusion index 7wwW1W2 
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


    public function __construct(string $data)
    {
        $this->setData($data);
    }
    
    public function setData(string $data)
    {
        if(!empty($data)) {
            $this->raw_cloud_vis = $data;
            $this->decoder = new LowCloudVisibilityDecoder($this->raw_cloud_vis);
            $this->setLcvGroup($this->decoder);
        } else {
            throw new Exception('LowCloudVisibility group cannot be empty!');
        }
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
        $this->inc_precip_group = $decoder->getIr();
    }
    
    /**
     * Sets weather indicator inclusion index 7wwW1W2
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setIncWeather(GroupDecoderInterface $decoder) : void
    {
        $this->inc_weather_group = $decoder->getIx();
    }
    
    /**
     * Sets base height of low clouds above sea level
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setHlowClouds(GroupDecoderInterface $decoder) : void
    {
        $this->height_low_clouds = $decoder->getH();
    }
    
    /**
     * Sets meteorological range of visibility
     * 
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setVisibility(GroupDecoderInterface $decoder) : void
    {
        $this->visibility = $decoder->getVV();
    }
}

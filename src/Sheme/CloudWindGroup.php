<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;

/**
 * The CloudWindGroup class contains methods for working with a group of total number of clouds and wind - 'Nddff'
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class CloudWindGroup implements GroupInterface
{
    private $raw_clouds_wind;

    private $decoder;

    /**
     * @var string Total clouds
     */
    private $total_clouds;

    /**
     * @var string Direction of the wind
     */
    private $direction_wind;

    /**
     * @var string Wind speed
     */
    private $wind_speed;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * Sets the parameters of a group of total number of clouds and wind
     * @param string $data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->raw_clouds_wind = $data;
            $this->decoder = new CloudWindDecoder($this->raw_clouds_wind);
            $this->setCloudWind($this->decoder);
        } else {
            throw new Exception('CloudWind group cannot be empty!');
        }
    }

    /**
     * Sets the group parameters of the total number of clouds and wind
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setCloudWind(GroupDecoderInterface $decoder) : void
    {
        $this->setTotalClouds($decoder);
        $this->setDirectionWind($decoder);
        $this->setWindSpeed($decoder);
    }

    /**
     * Sets the total number of clouds in a weather report
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setTotalClouds(GroupDecoderInterface $decoder) : void
    {
        $this->total_clouds = $decoder->getN();
    }

    /**
     * Sets the Direction Wind in a weather report
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setDirectionWind(GroupDecoderInterface $decoder) : void
    {
        $this->direction_wind = $decoder->getDd();
    }

    /**
     * Sets the Wind Speed in a weather report
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setWindSpeed(GroupDecoderInterface $decoder) : void
    {
        $this->wind_speed = $decoder->getVv();
    }
}
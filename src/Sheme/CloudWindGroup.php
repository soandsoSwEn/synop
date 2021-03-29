<?php


namespace Synop\Sheme;

use Exception;
use Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Fabrication\Unit;
use Synop\Fabrication\UnitInterface;

/**
 * The CloudWindGroup class contains methods for working with a group of total number of clouds and wind - 'Nddff'
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class CloudWindGroup implements GroupInterface
{
    private $raw_clouds_wind;

    /**
     * @var Unit class instance of the entity Unit
     */
    private $unit;

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

    public function __construct(string $data, UnitInterface $unit)
    {
        $this->setData($data);
        $this->setUnit($unit);
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
            $this->setDecoder(new CloudWindDecoder($this->raw_clouds_wind));
            $this->setCloudWind($this->getDecoder());
        } else {
            throw new Exception('CloudWind group cannot be empty!');
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

    /**
     * Sets total clouds value
     * @param string $totalClouds Total clouds
     */
    public function setTotalCloudsValue(string $totalClouds) : void
    {
        $this->total_clouds = $totalClouds;
    }

    /**
     * Sets direction of wind
     * @param string $directionWind Direction of wind
     */
    public function setDirectionWindValue(string $directionWind) : void
    {
        $this->direction_wind = $directionWind;
    }

    /**
     * Sets wind speed
     * @param string $windSpeed Wind speed
     */
    public function setWindSpeedValue(string $windSpeed) : void
    {
        $this->wind_speed = $windSpeed;
    }

    /**
     * @return GroupDecoderInterface
     */
    public function getDecoder() : GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns total clouds
     * @return string Total clouds
     */
    public function getTotalCloudsValue() : string
    {
        return $this->total_clouds;
    }

    /**
     * Returns direction of wind
     * @return string Direction of wind
     */
    public function getDirectionWindValue() : string
    {
        return $this->direction_wind;
    }

    /**
     * Returns wind speed
     * @return string Wind speed
     */
    public function getWindSpeedValue() : string
    {
        return $this->wind_speed;
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
        $this->setTotalCloudsValue($decoder->getN());
    }

    /**
     * Sets the Direction Wind in a weather report
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setDirectionWind(GroupDecoderInterface $decoder) : void
    {
        $this->setDirectionWindValue($decoder->getDd());
    }

    /**
     * Sets the Wind Speed in a weather report
     * @param GroupDecoderInterface $decoder
     * @return void
     */
    public function setWindSpeed(GroupDecoderInterface $decoder) : void
    {
        $this->setWindSpeedValue($decoder->getVv());
    }
}
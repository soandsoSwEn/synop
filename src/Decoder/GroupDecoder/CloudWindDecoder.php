<?php


namespace Synop\Decoder\GroupDecoder;

/**
 * The CloudWindDecoder contains methods for decoding a group of total clouds and wind
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class CloudWindDecoder implements GroupDecoderInterface
{
    private $raw_clouds_wind;

    /**
     * @var string[] Cloud Values
     */
    private $N = [
        '0' => '0',
        '1' => '1',
        '2' => '2-3',
        '3' => '4',
        '4' => '5',
        '5' => '6',
        '6' => '7-8',
        '7' => '9',
        '8' => '10',
        '9' => '-',
        '/' => ''
    ];

    public function __construct(string $raw_clouds_wind)
    {
        $this->raw_clouds_wind = $raw_clouds_wind;
    }

    /**
     * Returns the number of clouds
     * @return string
     */
    public function getN() : string
    {
        $N = substr($this->raw_clouds_wind, 0, 1);
        if (array_key_exists($N, $this->getNData())) {
            return $this->getNData()[$N];
        }
    }

    /**
     * Returns wind direction
     * @return int
     */
    public function getDd() : int
    {
        return intval(substr($this->raw_clouds_wind, 1, 2)) * 10;
    }

    /**
     * Returns the value of wind speed
     * @return int
     */
    public function getVv() : int
    {
        return intval(substr($this->raw_clouds_wind, 3, 2));
    }

    /**
     * Returns all values ​​for the number of clouds
     * @return array|string[]
     */
    private function getNData() : array
    {
        return $this->N;
    }
}
<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * The CloudWindDecoder contains methods for decoding a group of total clouds and wind
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class CloudWindDecoder implements GroupDecoderInterface
{
    /**
     * @var string Data of total clouds and wind
     */
    private $rawCloudsWind;

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

    public function __construct(string $rawCloudsWind)
    {
        $this->rawCloudsWind = $rawCloudsWind;
    }

    public function isGroup(ValidateInterface $validate): bool
    {
        return $validate->isValidGroup($this, [
            $this->getCodeFigureN(), $this->getCodeFigureDd(), $this->getCodeFigureVv()
        ]);
    }

    /**
     * Returns the number of clouds
     *
     * @return string
     */
    public function getN(): ?string
    {
        $N = substr($this->rawCloudsWind, 0, 1);
        if (array_key_exists($N, $this->getNData())) {
            return $this->getNData()[$N];
        }

        return null;
    }

    /**
     * Returns wind direction
     *
     * @return int
     */
    public function getDd(): int
    {
        return intval(substr($this->rawCloudsWind, 1, 2)) * 10;
    }

    /**
     * Returns the value of wind speed
     *
     * @return int
     */
    public function getVv(): int
    {
        return intval(substr($this->rawCloudsWind, 3, 2));
    }

    /**
     * Returns indicator and description of total amount of cloud for total number of clouds and wind - 'Nddff'
     *
     * @return string[] Indicator and description of total amount of cloud
     */
    public function getTotalCloudIndicator(): array
    {
        return ['N' => 'Total amount of cloud'];
    }

    /**
     * Returns indicator and description of wind direction for total number of clouds and wind - 'Nddff'
     *
     * @return string[] Indicator and description of wind direction
     */
    public function getWindDirectionIndicator(): array
    {
        return ['dd' => 'Wind direction in tens degrees'];
    }

    /**
     * Returns indicator and description of wind speed for total number of clouds and wind - 'Nddff'
     *
     * @return string[] Indicator and description of wind speed
     */
    public function getWindSpeedIndicator(): array
    {
        return ['ff' => 'Wind speed'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getTotalCloudIndicator()),
            key($this->getWindDirectionIndicator()),
            key($this->getWindSpeedIndicator()),
        ];
    }

    /**
     * Returns all values for the number of clouds
     *
     * @return array|string[]
     */
    private function getNData(): array
    {
        return $this->N;
    }

    /**
     * Return code figure of number of clouds
     *
     * @return string
     */
    private function getCodeFigureN(): string
    {
        return substr($this->rawCloudsWind, 0, 1);
    }

    /**
     * Return code figure of wind direction
     *
     * @return string
     */
    private function getCodeFigureDd(): string
    {
        return substr($this->rawCloudsWind, 1, 2);
    }

    /**
     * Return code figure of wind speed
     *
     * @return string
     */
    private function getCodeFigureVv(): string
    {
        return substr($this->rawCloudsWind, 3, 2);
    }
}

<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class PresentWeatherGroup contains methods for working with the Present weather group - 7wwW1W2
 *
 * @package Synop\Sheme
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class PresentWeatherGroup implements GroupInterface
{
    /**
     * @var string Code block of Present weather group
     */
    private $rawPresentWeather;

    /**
     * @var GroupDecoderInterface Initialized decoder object
     */
    private $decoder;

    /**
     * @var integer|null The Present Weather symbolic value
     */
    private $presentWeatherSymbol;

    /**
     * @var string|null The Present Weather string value
     */
    private $presentWeather;

    /**
     * @var integer|null The Past Weather symbolic value
     */
    private $pastWeatherSymbol;

    /**
     * @var array|null The Past Weather string values
     */
    private $pastWeather;

    public function __construct(string $data, ValidateInterface $validate)
    {
        $this->setData($data, $validate);
    }

    /**
     * Sets the initial data for Present weather group
     *
     * @param string $data Code block of Present weather group
     * @param ValidateInterface $validate Object for weather data validation
     * @throws Exception
     */
    public function setData(string $data, ValidateInterface $validate): void
    {
        if (!empty($data)) {
            $this->rawPresentWeather = $data;
            $this->setDecoder(new PresentWeatherDecoder($this->rawPresentWeather));
            $this->setPresentWeatherGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('PresentWeatherGroup group cannot be empty!');
        }
    }

    /**
     * Sets an initialized decoder object for Present weather group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets the Present Weather symbolic value
     *
     * @param string|null $presentWeatherSymbol The present weather symbolic value
     */
    public function setPresentWeatherSymbolValue(?string $presentWeatherSymbol): void
    {
        $this->presentWeatherSymbol = $presentWeatherSymbol;
    }

    /**
     * Sets present weather
     *
     * @param string|null $presentWeather The present weather
     */
    public function setPresentWeatherValue(?string $presentWeather): void
    {
        $this->presentWeather = $presentWeather;
    }

    /**
     * Sets past weather symbolic value
     *
     * @param string|null $pastWeatherSymbol The past weather symbolic value
     */
    public function setPastWeatherSymbolValue(?string $pastWeatherSymbol): void
    {
        $this->pastWeatherSymbol = $pastWeatherSymbol;
    }

    /**
     * Sets past weather
     *
     * @param null|array $pastWeather The past weather
     */
    public function setPastWeatherValue(?array $pastWeather): void
    {
        $this->pastWeather = $pastWeather;
    }

    /**
     * Returns an initialized decoder object for Present weather group
     *
     * @return GroupDecoderInterface Initialized decoder object
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns present weather symbolic value
     *
     * @return string The present weather symbolic value
     */
    public function getPresentWeatherSymbolValue(): string
    {
        return $this->presentWeatherSymbol;
    }

    /**
     * Returns present weather
     *
     * @return string The present weather
     */
    public function getPresentWeatherValue(): string
    {
        return $this->presentWeather;
    }

    /**
     * Returns past weather symbolic value
     *
     * @return int The past weather symbolic value
     */
    public function getPastWeatherSymbolValue(): string
    {
        return $this->pastWeatherSymbol;
    }

    /**
     * Returns Past weather
     *
     * @return array The past weather
     */
    public function getPastWeatherValue(): array
    {
        return $this->pastWeather;
    }

    /**
     * Sets the parameters of Present weather group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     */
    public function setPresentWeatherGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        if ($this->isPresentWeatherGroup($decoder, $validate)) {
            $this->setPresentWeatherSymbol($decoder);
            $this->setPresentWeather($decoder);
            $this->setPastWeatherSymbol($decoder);
            $this->setPastWeather($decoder);
        } else {
            $this->setPresentWeatherSymbol(null);
            $this->setPresentWeather(null);
            $this->setPastWeatherSymbol(null);
            $this->setPastWeather(null);
        }
    }

    /**
     * Validates a block of code against a Present weather group
     *
     * @param GroupDecoderInterface $decoder Initialized decoder object
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool
     */
    public function isPresentWeatherGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate, $this->getGroupIndicator());
    }

    /**
     * Sets the symbolic value of Present Weather
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPresentWeatherSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setPresentWeatherSymbolValue(null);
        } else {
            $this->setPresentWeatherSymbolValue($decoder->getPresentWeatherSymbol());
        }
    }

    /**
     * Sets the value of Present Weather
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPresentWeather(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setPresentWeatherValue(null);
        } else {
            $this->setPresentWeatherValue($decoder->getPresentWeather());
        }
    }

    /**
     * Sets the symbolic value of Past Weather
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPastWeatherSymbol(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setPastWeatherSymbolValue(null);
        } else {
            $this->setPastWeatherSymbolValue($decoder->getPastWeatherSymbol());
        }
    }

    /**
     * Sets the value of Past Weather
     *
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPastWeather(?GroupDecoderInterface $decoder): void
    {
        if (is_null($decoder)) {
            $this->setPastWeatherValue(null);
        } else {
            $this->setPastWeatherValue($decoder->getPastWeather());
        }
    }

    /**
     * Returns the indicator of the entire weather report group
     *
     * @return string Group indicator
     */
    public function getGroupIndicator(): string
    {
        return '7wwW1W2';
    }
}

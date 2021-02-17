<?php


namespace Synop\Sheme;

use Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Synop\Sheme\GroupInterface;
use Exception;


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
     * @var GroupDecoderInterface
     */
    private $decoder;

    /**
     * @var integer The Present Weather symbolic value
     */
    private $presentWeatherSymbol;

    /**
     * @var string The Present Weather string value
     */
    private $presentWeather;

    /**
     * @var integer The Past Weather symbolic value
     */
    private $pastWeatherSymbol;

    /**
     * @var array The Past Weather string values
     */
    private $pastWeather;

    public function __construct(string $data)
    {
        $this->setData($data);
    }

    /**
     * @param string $data
     * @throws Exception
     */
    public function setData(string $data) : void
    {
        if (!empty($data)) {
            $this->rawPresentWeather = $data;
            $this->decoder = new PresentWeatherDecoder($this->rawPresentWeather);
            $this->setPresentWeatherGroup($this->decoder);
        } else {
            throw new Exception('PresentWeatherGroup group cannot be empty!');
        }
    }

    /**
     * Sets the parameters of Present weather group
     * @param GroupDecoderInterface $decoder
     */
    public function setPresentWeatherGroup(GroupDecoderInterface $decoder)
    {
        if ($this->isPresentWeatherGroup($decoder)) {
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
     * @param GroupDecoderInterface $decoder
     * @return bool
     */
    public function isPresentWeatherGroup(GroupDecoderInterface $decoder) : bool
    {
        return $decoder->isGroup();
    }

    /**
     * Sets the symbolic value of Present Weather
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPresentWeatherSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->presentWeatherSymbol = null;
        } else {
            $this->presentWeatherSymbol = $decoder->getPresentWeatherSymbol();
        }
    }

    /**
     * Sets the value of Present Weather
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPresentWeather(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->presentWeather = null;
        } else {
            $this->presentWeather = $decoder->getPresentWeather();
        }
    }

    /**
     * Sets the symbolic value of Past Weather
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPastWeatherSymbol(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->pastWeatherSymbol = null;
        } else {
            $this->pastWeatherSymbol = $decoder->getPastWeatherSymbol();
        }
    }

    /**
     * Sets the value of Past Weather
     * @param GroupDecoderInterface|null $decoder
     */
    public function setPastWeather(?GroupDecoderInterface $decoder) : void
    {
        if (is_null($decoder)) {
            $this->pastWeather = null;
        } else {
            $this->pastWeather = $decoder->getPastWeather();
        }
    }
}
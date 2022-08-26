<?php

namespace Soandso\Synop\Sheme;

use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Decoder\GroupDecoder\DateDecoder;
use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * The DateGroup class contains methods for working with date group - 'YYGGiw'
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class DateGroup implements GroupInterface
{
    /**
     * @var string Code figure of date group
     */
    private $rawDate;

    /**
     * @var GroupDecoderInterface Decoder object for date group
     */
    private $decoder;

    /**
     * @var string Date (day of the month) of issuance of the meteorological weather report
     */
    private $day;

    /**
     * @var string Date (hour) of issuance of the meteorological weather report
     */
    private $hour;

    /**
     * @var array Index of wind speed units and how it is determined
     */
    private $iw;

    public function __construct(string $date, ValidateInterface $validate)
    {
        $this->setData($date, $validate);
    }

    /**
     * Sets the parameters of date group
     *
     * @param string $date Code figure of date group
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     * @throws Exception
     */
    public function setData(string $date, ValidateInterface $validate): void
    {
        if (!empty($date)) {
            $this->rawDate = $date;
            $this->setDecoder(new DateDecoder($this->rawDate));
            $this->setDateGroup($this->getDecoder(), $validate);
        } else {
            throw new Exception('Date group cannot be empty!');
        }
    }

    /**
     * Sets value for decoder object for date group
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @return void
     */
    public function setDecoder(GroupDecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * Sets day of the month of issuance of the meteorological weather report
     *
     * @param string $day
     * @return void
     */
    public function setDayValue(string $day): void
    {
        $this->day = $day;
    }

    /**
     * Sets hour of issuance of the meteorological weather report
     *
     * @param string $hour
     * @return void
     */
    public function setHourValue(string $hour): void
    {
        $this->hour = $hour;
    }

    /**
     * Sets index of wind speed units and how it is determined
     *
     * @param array $iw
     * @return void
     */
    public function setIwValue(array $iw): void
    {
        $this->iw = $iw;
    }

    /**
     * Returns value for decoder object for date group
     *
     * @return GroupDecoderInterface Decoder object for date group
     */
    public function getDecoder(): GroupDecoderInterface
    {
        return $this->decoder;
    }

    /**
     * Returns day of the month of issuance of the meteorological weather report
     *
     * @return string
     */
    public function getDayValue(): string
    {
        return $this->day;
    }

    /**
     * Returns hour of issuance of the meteorological weather report
     *
     * @return string
     */
    public function getHourValue(): string
    {
        return $this->hour;
    }

    /**
     * Returns index of wind speed units and how it is determined
     *
     * @return array
     */
    public function getIwValue(): array
    {
        return $this->iw;
    }

    /**
     * Sets date group parameters
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @param ValidateInterface $validate Object for weather data validation
     * @return void
     */
    public function setDateGroup(GroupDecoderInterface $decoder, ValidateInterface $validate)
    {
        $this->isDateGroup($decoder, $validate);
        $this->setDay($decoder);
        $this->setHour($decoder);
        $this->setIw($decoder);
    }

    /**
     * Sets parameter of day for the date group
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @return void
     */
    public function setDay(GroupDecoderInterface $decoder): void
    {
        $this->setDayValue($decoder->getDay());
    }

    /**
     * Sets parameter of hour for the date group
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @return void
     */
    public function setHour(GroupDecoderInterface $decoder): void
    {
        $this->setHourValue($decoder->getHour());
    }

    /**
     * Sets parameter of Index of wind speed units and how it is determined for the date group
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @return void
     */
    public function setIw(GroupDecoderInterface $decoder): void
    {
        $this->setIwValue($decoder->getIw());
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param GroupDecoderInterface $decoder Decoder object for date group
     * @param ValidateInterface $validate Object for weather data validation
     * @return bool
     */
    public function isDateGroup(GroupDecoderInterface $decoder, ValidateInterface $validate): bool
    {
        return $decoder->isGroup($validate);
    }
}

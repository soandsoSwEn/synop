<?php

namespace Soandso\Synop\Decoder\GroupDecoder;

use Exception;
use Soandso\Synop\Fabrication\ValidateInterface;

/**
 * Class PresentWeatherDecoder contains methods for decoding a group of Present weather
 *
 * @package Synop\Decoder\GroupDecoder
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class PresentWeatherDecoder implements GroupDecoderInterface
{
    /** Distinctive digit of the Present weather group */
    protected const DIGIT = '7';

    /**
     * @var string Present weather data
     */
    private $rawPresentWeather;

    /**
     * @var string[] Map correspondences of symbolic and present Weather values
     */
    private $presentWeatherMap = [
        '00' => 'Cloud Development not observed or not observable',
        '01' => 'Cloud generally dissolving or becoming less developed',
        '02' => 'State of sky on the whole unchanged',
        '03' => 'Clouds generally forming or developing',
        '04' => 'Visibility reduced by smoke, e.g. veld or forest fires, industrial smoke or volcanic ashes',
        '05' => 'Haze',
        '06' => 'Widespread dust in suspension in thr air, not raised by wind at or near the station at the time '
                 . 'of observation',
        '07' => 'Dust or sand raised by the wind at or near the station at the time of observation, but no '
                 . 'well-developed dust whirl(s) or sand whirl(s), and no duststorm or sandstorm seen: or, in '
                 . 'the case of ships blowing spray at the station',
        '08' => 'Well-developed dust whirl(s) or sand or sand whirl(s) seen at or near the station during the '
                 . 'preceding hour or at the time of observation, but no duststorm or sandstorm',
        '09' => 'Duststorm or sandstorm within sight at the time of observation, or at the station during '
                 . 'the preceding hour',
        '10' => 'Mist',
        '11' => 'Patches of shallow fog or ice fog a t the station whether or lan d sea, not deeper that about '
                 . '2 meters or land or 10 meters of sea',
        '12' => 'More or less continuous shallow fog or ice fog a t the station whether or lan d sea, not deeper '
                 . 'that about 2 meters or land or 10 meters of sea',
        '13' => 'Lightning visible, no thunder heard',
        '14' => 'Precipitation within sight, not reaching the ground or the surface of the sea',
        '15' => 'Precipitation within sight reaching the ground or the surface of the sea, but distant, i.e. '
                 . 'estimated to be more than 5 km from the station',
        '16' => 'Precipitation within sight reaching the ground of the surface of the sea, near to, but not '
                 . 'at the station',
        '17' => 'Thunderstorm, but no precipitation at the time observation',
        '18' => 'Squalls at or within sight of the station during the preceding hour or at the time of observation',
        '19' => 'Funnel cloud(s) or tuba at or within sight of the station during the preceding hour or at the '
                 . 'time of observation',
        '20' => 'Drizzle (not freezing) or snow grains not falling as shower(s)',
        '21' => 'Rain (not freezing) not falling as shower(s)',
        '22' => 'Snow not falling as shower(s)',
        '23' => 'Rain and snow or ice pellets not falling as shower(s)',
        '24' => 'Freezing drizzle or freezing rain not falling as shower(s)',
        '25' => 'Shower(s) of rain',
        '26' => 'Shower(s) of snow, or rain of snow',
        '27' => 'Shower(s) of hail, or rain of hail',
        '28' => 'Fog or ice fog',
        '29' => 'Thunderstorm (with or without precipitation)',
        '30' => 'Slight or moderate duststorm or sandstorm has decreased during the preceding hour',
        '31' => 'Slight or moderate duststorm or sandstorm no appreciable change during the preceding hour',
        '32' => 'Slight or moderate duststorm or sandstorm has begun or has increased during the preceding hour',
        '33' => 'Severe duststorm or sandstorm has decreased during the preceding hour',
        '34' => 'Severe duststorm or sandstorm no appreciable change during the preceding hour',
        '35' => 'Severe duststorm or sandstorm has begun or has increased during the preceding hour',
        '36' => 'Slight of moderate drifting snow generally low (below eye level)',
        '37' => 'Heavy drifting snow generally low (below eye level)',
        '38' => 'Slight or moderate blowing snow generally high (above eye level)',
        '39' => 'Heavy blowing snow generally high (above eye level)',
        '40' => 'Fog or ice fog at a distance at the time of observation, but not at the station during the '
                 . 'preceding hour, the fog or ice fog extending to a level above that of the observer',
        '41' => 'Fog or ice fog in patches',
        '42' => 'Fog or ice fog, sky visible has become thinner during the preceding hour',
        '43' => 'Fog or ice fog, sky invisible has become thinner during the preceding hour',
        '44' => 'Fog or ice fog, sky visible no appreciable change during the preceding hour',
        '45' => 'Fog or ice fog, sky invisible no appreciable change during the preceding hour',
        '46' => 'Fog or ice fog, sky visible has begun or has become thicker during the preceding hour',
        '47' => 'Fog or ice fog, sky invisible has begun or has become thicker during the preceding hour',
        '48' => 'Fog, depositing rime, sky visible',
        '49' => 'Fog, depositing rime, sky invisible',
        '50' => 'Drizzle, not freezing, intermittent slight at time of observation',
        '51' => 'Drizzle, not freezing, continuous slight at time of observation',
        '52' => 'Drizzle, not freezing, intermittent moderate at time of observation',
        '53' => 'Drizzle, not freezing, continuous moderate at time of observation',
        '54' => 'Drizzle, not freezing, intermittent heavy (dense) at time of observation',
        '55' => 'Drizzle, not freezing, continuous heavy (dense) at time of observation',
        '56' => 'Drizzle, freezing, slight',
        '57' => 'Drizzle, freezing, moderate or heavy (dense)',
        '58' => 'Drizzle and rain, slight',
        '59' => 'Drizzle and rain, moderate or heavy',
        '60' => 'Rain, not freezing, intermittent slight at time of observation',
        '61' => 'Rain, not freezing, continuous slight at time of observation',
        '62' => 'Rain, not freezing, intermittent moderate at time of observation',
        '63' => 'Rain, not freezing, continuous moderate at time of observation',
        '64' => 'Rain, not freezing, intermittent heavy at time of observation',
        '65' => 'Rain, not freezing, continuous heavy at time of observation',
        '66' => 'Rain, freezing, slight',
        '67' => 'Rain, freezing, moderate or heavy',
        '68' => 'Rain or drizzle and snow, slight',
        '69' => 'Rain or drizzle and snow moderate or heavy',
        '70' => 'Intermittent fall of snowflakes slight at time of observation',
        '71' => 'Continuous fall of snowflakes slight at time of observation',
        '72' => 'Intermittent fall of snowflakes moderate at time of observation',
        '73' => 'Continuous fall of snowflakes moderate at time of observation',
        '74' => 'Intermittent fall of snowflakes heavy at time of observation',
        '75' => 'Continuous fall of snowflakes heavy at time of observation',
        '76' => 'Dmond dust (with or without fog)',
        '77' => 'Snow grains (with or without fog)',
        '78' => 'Isolated star like snow crystals (with or without fog)',
        '79' => 'Ice pellets',
        '80' => 'Rain shower(s) slight',
        '81' => 'Rain shower(s) moderate or heavy',
        '82' => 'Rain shower(s) violent',
        '83' => 'Shower(s) of rain and snow mixed slight',
        '84' => 'Shower(s) of rain and snow mixed moderate or heavy',
        '85' => 'Snow shower(s) slight',
        '86' => 'Snow shower(s) moderate or heavy',
        '87' => 'Shower(s) of snow pellets or small hail, with or without rain and snow mixed slight',
        '88' => 'Shower(s) of snow pellets or small hail, with or without rain and snow mixed moderate or heavy',
        '89' => 'Shower(s) of snow with or without rain or rain and snow mixed not associated with thunder slight',
        '90' => 'Shower(s) of snow with or without rain or rain and snow mixed not associated with thunder moderate '
                 . 'or heavy',
        '91' => 'Slight rain at time of observation',
        '92' => 'Moderate or heavy rain at time of observation',
        '93' => 'Slight now or rain and snow mixed, or hail at time observation',
        '94' => 'Moderate or heavy snow or rain and snow mixed, or hail at time of observation',
        '95' => 'Thunderstorm, slight or moderate, without hail or with rain and/or snow at time of observation',
        '96' => 'Thunderstorm, slight or moderate with hail at time of observation',
        '97' => 'Thunderstorm, heavy, without hail but with rain and/or snow at time of observation',
        '98' => 'Thunderstorm combined with duststorm of sandstorm at time of observation',
        '99' => 'Thunderstorm, heavy, with hail at time of observation',
        '/' => null
    ];

    /**
     * @var string[] Map correspondences of symbolic and past Weather values
     */
    private $pastWeatherMap = [
        '0' => 'Cloud covering 1/2 or less of the sky throughout the appropriate period',
        '1' => 'Cloud covering more than 1/2 of the sky during part of the appropriate period and covering 1/2 or '
                . 'less during part of the period',
        '2' => 'Cloud covering more than 1/2 of the sky throughout the appropriate period',
        '3' => 'Sandstorm, duststorm or blowing snow',
        '4' => 'Fog or ice fog or thick haze (visibility less than 1,000 m)',
        '5' => 'Drizzle',
        '6' => 'Rain',
        '7' => 'Snow or rain and snow mixed or diamond dust',
        '8' => 'Shower(s)',
        '9' => 'Thunderstorm(s) with or without precipitation',
        '/' => null
    ];

    public function __construct(string $rawPresentWeather)
    {
        $this->rawPresentWeather = $rawPresentWeather;
    }

    /**
     * Returns the result of checking the validity of the group
     *
     * @param ValidateInterface $validate
     * @param string $groupIndicator Group figure indicator
     * @return bool
     * @throws Exception
     */
    public function isGroup(ValidateInterface $validate, string $groupIndicator): bool
    {
        $distinguishingDigit = substr($this->rawPresentWeather, 0, 1);

        if (strcasecmp($distinguishingDigit, self::DIGIT) == 0) {
            $validate->isValidGroup(
                $this,
                $groupIndicator,
                [
                    $this->getCodeFigureIndicator(),
                    $this->getCodeFigurePresentWeather(),
                    $this->getCodeFigurePastWeather()
                ]
            );

            return true;
        }

        return false;
    }

    /**
     * Returns the Present Weather symbol value
     * @return int
     */
    public function getPresentWeatherSymbol(): int
    {
        $symbol = intval(substr($this->rawPresentWeather, 1, 2));
        return sprintf("%1$02d", $symbol);
    }

    /**
     * Returns the Present Weather value
     *
     * @return string
     * @throws Exception
     */
    public function getPresentWeather(): string
    {
        $ww = substr($this->rawPresentWeather, 1, 2);
        if (array_key_exists($ww, $this->presentWeatherMap)) {
            return $this->presentWeatherMap[$ww];
        } else {
            throw new Exception('Invalid data of Present Weather');
        }
    }

    /**
     * Returns the Past Weather symbol value
     *
     * @return string
     */
    public function getPastWeatherSymbol(): int
    {
        $symbol = intval(substr($this->rawPresentWeather, 3, 2));
        return sprintf("%1$02d", $symbol);
    }

    /**
     * Returns the Past Weather symbol value
     *
     * @return string[]
     * @throws Exception
     */
    public function getPastWeather(): array
    {
        $W1W2 = substr($this->rawPresentWeather, 3, 2);
        $W1 = substr($W1W2, 0, 1);
        $W2 = substr($W1W2, 1, 1);

        if (array_key_exists($W1, $this->pastWeatherMap) && array_key_exists($W2, $this->pastWeatherMap)) {
            $pastWeather = ['W1' => $this->pastWeatherMap[$W1], 'W2' => $this->pastWeatherMap[$W2]];
            return $pastWeather;
        } else {
            throw new Exception('Invalid data of Past Weather');
        }
    }

    /**
     * Returns indicator and description of group indicator for present weather group - 7wwW1W2
     *
     * @return string[] Indicator and description of present weather
     */
    public function getIndicatorGroup(): array
    {
        return ['7' => 'Indicator'];
    }

    /**
     * Returns indicator and description of present weather indicator for present weather group - 7wwW1W2
     *
     * @return string[] Indicator and description of present weather indicator
     */
    public function getPresentWeatherIndicator(): array
    {
        return ['ww' => 'Present weather'];
    }

    /**
     * Returns indicator and description of past weather indicator for present weather group - 7wwW1W2
     *
     * @return string[] Indicator and description of past weather indicator
     */
    public function getPastWeatherIndicator(): array
    {
        return ['W1W2' => 'Past weather'];
    }

    public function getGroupIndicators()
    {
        return [
            key($this->getIndicatorGroup()),
            key($this->getPresentWeatherIndicator()),
            key($this->getPastWeatherIndicator()),
        ];
    }

    /**
     * Return code figure of weather group data
     *
     * @return false|string
     */
    private function getCodeFigureIndicator()
    {
        return substr($this->rawPresentWeather, 0, 1);
    }

    /**
     * Return code figure of Present weather
     *
     * @return false|string
     */
    private function getCodeFigurePresentWeather()
    {
        return substr($this->rawPresentWeather, 1, 2);
    }

    /**
     * Return code figure of Past weather
     *
     * @return false|string
     */
    private function getCodeFigurePastWeather()
    {
        return substr($this->rawPresentWeather, 3, 2);
    }
}

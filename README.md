Synop (AAXX/BBXX) weather report decoder
========================================

PHP library for decoding SYNOP (AAXX) strings of weather reports.

You can try out the library on the [demo site](http://synop-application.soandso.biz/)

Introduction
------------

This library component is a parser for decoding the raw [SYNOP](https://en.wikipedia.org/wiki/SYNOP) weather report.

SYNOP (surface synoptic observations) is a numerical code (called FM-12 by WMO) used for reporting weather observations made by manned and automated weather stations.
A report consists of groups of numbers and symbols  describing meteorological parameters, that observes at a weather station.

See more details at **_Manual of Codes. International of codes. Volume I.1. World Meteorological Organization, 2011_**

There are such forms of surface synoptic observations

* SYNOP - Report of surface observation from a fixed land station
* SHIP - Report of surface observation from a sea station
* SYNOP MOBILE - Report of surface observation from a mobile land station

The current version of the library works with the SYNOP code form.

Requirements
-----------

This library only requires PHP >= 7.2

Setup
-----

Add the library to your composer.json file in your project:

```bash
{
  "require": {
      "soandso/synop": "0.*"
  }
}
```

Use [composer](http://getcomposer.org) to install the library:

```bash
$ php composer.phar install
```

Composer will install synop decoder library inside your vendor folder. Then you can add the following to your
.php files to use the library with Autoloading.

```php
require_once(__DIR__ . '/vendor/autoload.php');
```

You can also use composer on the command line to require and install Grouping:

```bash
$ php composer.phar require soandso/synop
```

Usage
-----

Instantiate the ```Report``` class with the SYNOP weather report string.
To check the ```validity``` of the original weather report, use the validate method of the ```Report``` object.

If the summary is in a valid format, you can get the decoded SYNOP parameters. The ```Report``` object contains methods for getting decoded data.

Available Methods of the Report Object

```php
require_once dirname(__FILE__) . '/vendor/autoload.php';

use Synop\Report;

$report = new Report('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=');

//check validate format
$report->validate(); //true

$report->parse();

//Group YYGGiw
//Get a type of weather station
$report->getTypeStation(); //AAXX

//Get a date (day) of a meteorological report
$report->getDay(); //07

//Get a time (hour) of a meteorological report
$report->getTime(); //18

//Get a unit of measure for wind speed
$report->getUnitWind(); //m/s

//Get a type of wind measurement
$report->getWindDetection(); //Instrumental

//Group IIiii
//Area number of meteorological station
$report->getAreaNumber(); //33

//Number of meteorological station
$report->getStationNumber(); //837

//station index
$report->getStationIndex(); //33837

//Group IrIxhVV
//Index inclusion the precipitation group 6RRRtr
$report->getInclusionPrecipitation(); //Included in section 1

//Weather indicator inclusion index 7wwW1W2
$report->getInclusionWeather(); //Included

//Type station operation
$report->getTypeStationOperation(); //Manned

//Base height of low clouds above sea level
$report->getHeightLowCloud(); //600-1000

//Unit for a base height of low clouds above sea level
$report->getHeightLowCloudUnit(); //m

//Meteorological range of visibility
$report->getVisibility(); //45

//Unit for a meteorological range of visibility
$report->getVisibilityUnit(); //km

//Group NDDff
//Total clouds
$report->getTotalAmountCloud(); //10

//Direction of wind
$report->getWindDirection(); //310

//Unit for direction of wind
$report->getWindDirectionUnit(); //degrees

//Wind speed
$report->getWindSpeed(); //2

//Unit for wind speed
$report->getWindSpeedUnit(); //m/s

//Group 1SnTTT
//Air temperature
$report->getAirTemperature(); //3.9

//Unit for air temperature
$report->getAirTemperatureUnit(); //degree C

//Group 2SnTdTdTd
//Dew point temperature
$report->getDewPointTemperature(); //-0.7

//Unit for dew point temperature
$report->getDewPointTemperatureUnit(); //degree C

//Group 3P0P0P0
//Atmospheric pressure at station level
$report->getStationLevelPressure(); //1004.9

//Unit for atmospheric pressure at station level
$report->getStationLevelPressureUnit(); //hPa

//Group 4PPPP
//Atmospheric pressure reduced to mean sea level
$report->getSeaLevelPressure(); //1010.1

//Unit for atmospheric pressure reduced to mean sea level
$report->getSeaLevelPressureUnit(); //hPa

//Group 5appp
//Pressure change over last three hours
$report->getBaricTendency(); //3.5

//Unit for pressure change over last three hours
$report->getBaricTendencyUnit(); //hPa

//Group 6RRRtr
//Value of amount of rainfall
$report->getAmountRainfall(); //1

//Unit for amount of rainfall
$report->getAmountRainfallUnit(); //mm

//Duration period of rainfall
$report->getDurationPeriodRainfall(); //At 0600 and 1800 GMT

//Group 7wwW1W2
//present weather
$report->getPresentWeather(); //State of sky on the whole unchanged

//Past weather
$report->getPastWeather();
//array(2) {
//  ["W1"]=>
//  string(9) "Shower(s)"
//  ["W2"]=>
//  string(73) "Cloud covering more than 1/2 of the sky throughout the appropriate period"
//}

//Group 8NhClCmCH
//Amount of low or middle cloud
$report->getAmountLowOrMiddleCloud(); //2 eight of sky covered

//Form of low cloud
$report->getFormLowCloud(); //Stratocumulus not resulting from the spreading out of Cumulus

//Form of medium cloud
$report->getFormMediumCloud(); //Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole

//Form of high cloud
$report->getFormHighCloud(); //Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds

//Section 3
//Group 1SnTxTxTx
//Maximum air temperature
$report->getMaxAirTemperature(); //9.1

//Unit for maximum air temperature
$report->getMaxAirTemperatureUnit(); //degree C

//Group 2SnTnTnTn
//Minimum air temperature
$report->getMinAirTemperature(); //NULL

//Unit for minimum air temperature
$report->getMinAirTemperatureUnit(); //NULL

//Group 3ESnTgTg
//state of ground for case ground without snow or measurable ice cover
$report->getStateGroundWithoutSnow(); //NULL

//Grass minimum temperature for case ground without snow or measurable ice cover
$report->getMinTemperatureOfGroundWithoutSnow(); //NULL

//Unit for grass minimum temperature for case ground without snow or measurable ice cover
$report->getMinTemperatureOfGroundWithoutSnowUnit(); //NULL

//Group 4Esss
//State of ground for case ground with snow or measurable ice cover
$report->getStateGroundWithSnow(); //NULL

//Depth of snow
$report->getDepthSnow(); //NULL

//Unit for depth of snow
$report->getDepthSnowUnit(); //NULL

//Group 55SSS
//Duration of daily sunshine
$report->getDurationSunshine(); //NULL

//Unit for a duration of daily sunshine
$report->getDurationSunshineUnit(); //NULL

//Group 6RRRtr
//Amount of rainfall
$report->getRegionalExchangeAmountRainfall(); //NULL

//Unit for amount of rainfall
$report->getRegionalExchangeAmountRainfallUnit(); //NULL

//Duration period of rainfall
$report->getRegionalExchangeDurationPeriodRainfall(); //NULL

//Group 8NsChshs
//Amount of individual cloud layer
$report->getAmountIndividualCloudLayer(); //NULL

//Form of cloud (additional cloud information)
$report->getFormCloud(); //NULL

//Height of base of cloud layer (additional cloud information)
$report->getHeightCloud(); //NULL

//Unit for a form of cloud (additional cloud information)
$report->getHeightCloudUnit(); //NULL

```
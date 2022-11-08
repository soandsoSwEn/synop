<?php

namespace Soandso\Synop\Tests\Fabrication;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\AmountRainfallDecoder;
use Soandso\Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Soandso\Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Soandso\Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Soandso\Synop\Decoder\GroupDecoder\DateDecoder;
use Soandso\Synop\Decoder\GroupDecoder\DewPointTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithoutSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Soandso\Synop\Decoder\GroupDecoder\MslPressureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\PresentWeatherDecoder;
use Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\SunshineRadiationDataDecoder;
use Soandso\Synop\Decoder\GroupDecoder\TypeDecoder;
use Soandso\Synop\Fabrication\Validate;

class ValidateTest extends TestCase
{
    private $validate;

    protected function setUp(): void
    {
        $this->validate = new Validate('AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=');
    }

    protected function tearDown(): void
    {
        unset($this->validate);
    }

    public function testSuccessTypeValid()
    {
        $typeDecoder = Mockery::mock(TypeDecoder::class);

        $this->assertTrue($this->validate->typeValid($typeDecoder, ['AAXX']));
    }

    public function testSuccessErrorsEmptyTypeValid()
    {
        $typeDecoder = Mockery::mock(TypeDecoder::class);
        $this->validate->typeValid($typeDecoder, ['AAXX']);

        $reflector = new \ReflectionClass(Validate::class);
        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $this->assertEquals(count($value), 0);
    }

    public function testErrorTypeValid()
    {
        $typeDecoder = Mockery::mock(TypeDecoder::class);
        $typeDecoder->shouldReceive('getTypeReportIndicator')
            ->andReturn(['AAXX/BBXX' => 'Synoptic Code Identifier']);

        $this->assertFalse($this->validate->typeValid($typeDecoder, ['AACC']));
    }

    public function testErrorSourceErrorsTypeValid()
    {
        $typeDecoder = Mockery::mock(TypeDecoder::class);
        $typeDecoder->shouldReceive('getTypeReportIndicator')
            ->andReturn(['AAXX/BBXX' => 'Synoptic Code Identifier']);

        $this->validate->typeValid($typeDecoder, ['AACC']);

        $reflector = new \ReflectionClass(Validate::class);
        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $expected = [
            'AAXX/BBXX' => [
                'description' =>'Synoptic Code Identifier',
                'code' => 'AACC',
                'error' => 'The summary type group data does not match the specified format; Code group - AACC'
            ]
        ];

        $this->assertEquals($expected, $value);
    }

    public function testSuccessDateValid()
    {
        $dateDecoder = Mockery::mock(DateDecoder::class);
        $dateDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['YY', 'GG', 'iw']);

        $this->assertTrue($this->validate->dateValid($dateDecoder, ['07', '18', ['Instrumental', 'm/s']]));
    }

    public function testErrorDayDateValid()
    {
        $dateDecoder = Mockery::mock(DateDecoder::class);
        $dateDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['YY', 'GG', 'iw']);
        $dateDecoder->shouldReceive('getDayIndicator')
            ->andReturn(['YY' => 'Day of the month of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getHourIndicator')
            ->andReturn(['GG' => 'Hour of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getSpeedUnitsIndicator')
            ->andReturn(['iw' => 'Index of wind speed units and how it is determined']);

        $this->assertFalse($this->validate->dateValid($dateDecoder, ['32', '18', ['Instrumental', 'm/s']]));
    }

    public function testErrorHourDateValid()
    {
        $dateDecoder = Mockery::mock(DateDecoder::class);
        $dateDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['YY', 'GG', 'iw']);
        $dateDecoder->shouldReceive('getDayIndicator')
            ->andReturn(['YY' => 'Day of the month of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getHourIndicator')
            ->andReturn(['GG' => 'Hour of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getSpeedUnitsIndicator')
            ->andReturn(['iw' => 'Index of wind speed units and how it is determined']);

        $this->assertFalse($this->validate->dateValid($dateDecoder, ['31', 'two', ['Instrumental', 'm/s']]));
    }

    public function testErrorIndexSpeedUnitDateValid()
    {
        $dateDecoder = Mockery::mock(DateDecoder::class);
        $dateDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['YY', 'GG', 'iw']);
        $dateDecoder->shouldReceive('getDayIndicator')
            ->andReturn(['YY' => 'Day of the month of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getHourIndicator')
            ->andReturn(['GG' => 'Hour of issuance of the meteorological weather report']);
        $dateDecoder->shouldReceive('getSpeedUnitsIndicator')
            ->andReturn(['iw' => 'Index of wind speed units and how it is determined']);

        $this->assertFalse($this->validate->dateValid($dateDecoder, ['31', 'two', 'm/s']));
    }

    public function testSuccessIndexValid()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getGroupIndicators')->andReturn(['II', 'iii']);

        $this->assertTrue($this->validate->indexValid($indexDecoder, ['33', '837']));
    }

    public function testErrorIndexValid()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getGroupIndicators')->andReturn(['II', 'iii']);
        $indexDecoder->shouldReceive('getStationAreaIndicator')
            ->andReturn(['II' => 'Area station']);
        $indexDecoder->shouldReceive('getStationIndexIndicator')
            ->andReturn(['iii' => 'Station index']);

        $this->assertFalse($this->validate->indexValid($indexDecoder, ['tree', '83']));
    }

    public function testErrorAreaNumberIndexValid()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getGroupIndicators')->andReturn(['II', 'iii']);
        $indexDecoder->shouldReceive('getStationAreaIndicator')
            ->andReturn(['II' => 'Area station']);
        $indexDecoder->shouldReceive('getStationIndexIndicator')
            ->andReturn(['iii' => 'Station index']);

        $this->assertFalse($this->validate->indexValid($indexDecoder, ['tree', '837']));
    }

    public function testErrorStationNumberIndexValid()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getGroupIndicators')->andReturn(['II', 'iii']);
        $indexDecoder->shouldReceive('getStationAreaIndicator')
            ->andReturn(['II' => 'Area station']);
        $indexDecoder->shouldReceive('getStationIndexIndicator')
            ->andReturn(['iii' => 'Station index']);

        $this->assertFalse($this->validate->indexValid($indexDecoder, ['33', '83']));
    }

    public function testSuccessLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);

        $this->assertTrue($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['1', '1', '5', '83']));
    }

    public function testErrorLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetPrecipitationDataIndicator')
            ->andReturn(['ir' => 'Inclusion omission of precipitation data']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetWeatherGroupIndicator')
            ->andReturn(['ix' => 'Inclusion omission of weather group']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetHeightCloudIndicator')
            ->andReturn(['h' => 'Height of base of lowest cloud']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetVisibilityIndicator')
            ->andReturn(['VV' => 'Horizontal visibility']);

        $this->assertFalse($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['5', '9', '55', '8']));
    }

    public function testErrorIndexPrecipitationLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetPrecipitationDataIndicator')
            ->andReturn(['ir' => 'Inclusion omission of precipitation data']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetWeatherGroupIndicator')
            ->andReturn(['ix' => 'Inclusion omission of weather group']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetHeightCloudIndicator')
            ->andReturn(['h' => 'Height of base of lowest cloud']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetVisibilityIndicator')
            ->andReturn(['VV' => 'Horizontal visibility']);

        $this->assertFalse($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['5', '1', '5', '83']));
    }

    public function testErrorValuesTypeIndicatorLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetPrecipitationDataIndicator')
            ->andReturn(['ir' => 'Inclusion omission of precipitation data']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetWeatherGroupIndicator')
            ->andReturn(['ix' => 'Inclusion omission of weather group']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetHeightCloudIndicator')
            ->andReturn(['h' => 'Height of base of lowest cloud']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetVisibilityIndicator')
            ->andReturn(['VV' => 'Horizontal visibility']);

        $this->assertFalse($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['1', '9', '5', '83']));
    }

    public function testErrorValueHeightLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetPrecipitationDataIndicator')
            ->andReturn(['ir' => 'Inclusion omission of precipitation data']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetWeatherGroupIndicator')
            ->andReturn(['ix' => 'Inclusion omission of weather group']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetHeightCloudIndicator')
            ->andReturn(['h' => 'Height of base of lowest cloud']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetVisibilityIndicator')
            ->andReturn(['VV' => 'Horizontal visibility']);

        $this->assertFalse($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['1', '1', '55', '83']));
    }

    public function testErrormeteorologicaVisibilityLowCloudVisibilityValid()
    {
        $lowCloudVisibilityDecoder = Mockery::mock(LowCloudVisibilityDecoder::class);
        $lowCloudVisibilityDecoder->shouldReceive('getGroupIndicators')->andReturn(['ir', 'ix', 'h', 'VV']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetPrecipitationDataIndicator')
            ->andReturn(['ir' => 'Inclusion omission of precipitation data']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetWeatherGroupIndicator')
            ->andReturn(['ix' => 'Inclusion omission of weather group']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetHeightCloudIndicator')
            ->andReturn(['h' => 'Height of base of lowest cloud']);
        $lowCloudVisibilityDecoder->shouldReceive('getGetVisibilityIndicator')
            ->andReturn(['VV' => 'Horizontal visibility']);

        $this->assertFalse($this->validate->lowCloudVisibilityValid($lowCloudVisibilityDecoder, ['1', '1', '5', '8']));
    }

    public function testSuccessCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);

        $this->assertTrue($this->validate->cloudWindGroupValid($cloudWindDecoder, ['8', '31', '02']));
    }

    public function testErrorCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);
        $cloudWindDecoder->shouldReceive('getTotalCloudIndicator')
            ->andReturn(['N' => 'Total amount of cloud']);
        $cloudWindDecoder->shouldReceive('getWindDirectionIndicator')
            ->andReturn(['dd' => 'Wind direction in tens degrees']);
        $cloudWindDecoder->shouldReceive('getWindSpeedIndicator')
            ->andReturn(['ff' => 'Wind speed']);

        $this->assertFalse($this->validate->cloudWindGroupValid($cloudWindDecoder, ['\\', '\\', '38']));
    }

    public function testErrorNumberCloudsCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);
        $cloudWindDecoder->shouldReceive('getTotalCloudIndicator')
            ->andReturn(['N' => 'Total amount of cloud']);
        $cloudWindDecoder->shouldReceive('getWindDirectionIndicator')
            ->andReturn(['dd' => 'Wind direction in tens degrees']);
        $cloudWindDecoder->shouldReceive('getWindSpeedIndicator')
            ->andReturn(['ff' => 'Wind speed']);

        $this->assertFalse($this->validate->cloudWindGroupValid($cloudWindDecoder, ['\\', '31', '02']));
    }

    public function testErrorWindDirectionCloudsCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);
        $cloudWindDecoder->shouldReceive('getTotalCloudIndicator')
            ->andReturn(['N' => 'Total amount of cloud']);
        $cloudWindDecoder->shouldReceive('getWindDirectionIndicator')
            ->andReturn(['dd' => 'Wind direction in tens degrees']);
        $cloudWindDecoder->shouldReceive('getWindSpeedIndicator')
            ->andReturn(['ff' => 'Wind speed']);

        $this->assertFalse($this->validate->cloudWindGroupValid($cloudWindDecoder, ['8', '\\\\', '02']));
    }

    public function testErrorWindDirectionValueCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);
        $cloudWindDecoder->shouldReceive('getTotalCloudIndicator')
            ->andReturn(['N' => 'Total amount of cloud']);
        $cloudWindDecoder->shouldReceive('getWindDirectionIndicator')
            ->andReturn(['dd' => 'Wind direction in tens degrees']);
        $cloudWindDecoder->shouldReceive('getWindSpeedIndicator')
            ->andReturn(['ff' => 'Wind speed']);

        $this->assertFalse($this->validate->cloudWindGroupValid($cloudWindDecoder, ['8', '38', '02']));
    }

    public function testErrorWindSpeedCloudsCloudWindGroupValid()
    {
        $cloudWindDecoder = Mockery::mock(CloudWindDecoder::class);
        $cloudWindDecoder->shouldReceive('getGroupIndicators')->andReturn(['N', 'dd', 'ff']);
        $cloudWindDecoder->shouldReceive('getTotalCloudIndicator')
            ->andReturn(['N' => 'Total amount of cloud']);
        $cloudWindDecoder->shouldReceive('getWindDirectionIndicator')
            ->andReturn(['dd' => 'Wind direction in tens degrees']);
        $cloudWindDecoder->shouldReceive('getWindSpeedIndicator')
            ->andReturn(['ff' => 'Wind speed']);

        $this->assertFalse($this->validate->cloudWindGroupValid($cloudWindDecoder, ['8', '31', '2']));
    }

    public function testSuccessAirTemperatureGroupValid()
    {
        $airTemperatureDecoder = Mockery::mock(AirTemperatureDecoder::class);
        $airTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['1', 'Sn', 'TTT']);

        $this->assertTrue($this->validate->airTemperatureGroupValid($airTemperatureDecoder, ['1', '0', '039']));
    }

    public function testErrorAirTemperatureGroupValid()
    {
        $airTemperatureDecoder = Mockery::mock(AirTemperatureDecoder::class);
        $airTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['1', 'Sn', 'TTT']);
        $airTemperatureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['1' => 'Indicator']);
        $airTemperatureDecoder->shouldReceive('getSignTemperatureIndicator')
            ->andReturn(['Sn' => 'Sign of temperature']);
        $airTemperatureDecoder->shouldReceive('getDryBulbTemperatureIndicator')
            ->andReturn(['TTT' => 'Dry-bulb temperature in tenths of a degree']);

        $this->assertFalse($this->validate->airTemperatureGroupValid($airTemperatureDecoder, ['\\', '5', 'nil']));
    }

    public function testErrorDistinctiveNumberAirTemperatureGroupValid()
    {
        $airTemperatureDecoder = Mockery::mock(AirTemperatureDecoder::class);
        $airTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['1', 'Sn', 'TTT']);
        $airTemperatureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['1' => 'Indicator']);
        $airTemperatureDecoder->shouldReceive('getSignTemperatureIndicator')
            ->andReturn(['Sn' => 'Sign of temperature']);
        $airTemperatureDecoder->shouldReceive('getDryBulbTemperatureIndicator')
            ->andReturn(['TTT' => 'Dry-bulb temperature in tenths of a degree']);

        $this->assertFalse($this->validate->airTemperatureGroupValid($airTemperatureDecoder, ['5', '0', '039']));
    }

    public function testErrorSignAirTemperatureGroupValid()
    {
        $airTemperatureDecoder = Mockery::mock(AirTemperatureDecoder::class);
        $airTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['1', 'Sn', 'TTT']);
        $airTemperatureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['1' => 'Indicator']);
        $airTemperatureDecoder->shouldReceive('getSignTemperatureIndicator')
            ->andReturn(['Sn' => 'Sign of temperature']);
        $airTemperatureDecoder->shouldReceive('getDryBulbTemperatureIndicator')
            ->andReturn(['TTT' => 'Dry-bulb temperature in tenths of a degree']);

        $this->assertFalse($this->validate->airTemperatureGroupValid($airTemperatureDecoder, ['1', '5', '039']));
    }

    public function testErrorAirTemperatureAirTemperatureGroupValid()
    {
        $airTemperatureDecoder = Mockery::mock(AirTemperatureDecoder::class);
        $airTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['1', 'Sn', 'TTT']);
        $airTemperatureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['1' => 'Indicator']);
        $airTemperatureDecoder->shouldReceive('getSignTemperatureIndicator')
            ->andReturn(['Sn' => 'Sign of temperature']);
        $airTemperatureDecoder->shouldReceive('getDryBulbTemperatureIndicator')
            ->andReturn(['TTT' => 'Dry-bulb temperature in tenths of a degree']);

        $this->assertFalse($this->validate->airTemperatureGroupValid($airTemperatureDecoder, ['1', '0', '/39']));
    }

    public function testSuccessDewPointTemperatureGroupValid()
    {
        $dewPointTemperatureDecoder = Mockery::mock(DewPointTemperatureDecoder::class);
        $dewPointTemperatureDecoder->shouldReceive('getGroupIndicators')->andReturn(['2', 'Sn', 'TdTdTd']);

        $this->assertTrue($this->validate->dewPointTemperatureGroupValid($dewPointTemperatureDecoder, ['2', '1', '007']));
    }

    public function testSuccessStLPressureGroupValid()
    {
        $stlPressureDecoder = Mockery::mock(StLPressureDecoder::class);
        $stlPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'PPPP']);

        $this->assertTrue($this->validate->stLPressureGroupValid($stlPressureDecoder, ['3', '0049']));
    }

    public function testErrorStLPressureGroupValid()
    {
        $stlPressureDecoder = Mockery::mock(StLPressureDecoder::class);
        $stlPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'PPPP']);
        $stlPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $stlPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean station level) in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->stLPressureGroupValid($stlPressureDecoder, ['three', '49']));
    }

    public function testErrorIndicatorStLPressureGroupValid()
    {
        $stlPressureDecoder = Mockery::mock(StLPressureDecoder::class);
        $stlPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'PPPP']);
        $stlPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $stlPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean station level) in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->stLPressureGroupValid($stlPressureDecoder, ['1', '0049']));
    }

    public function testErrorAtmosphericPressureStLPressureGroupValid()
    {
        $stlPressureDecoder = Mockery::mock(StLPressureDecoder::class);
        $stlPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'PPPP']);
        $stlPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $stlPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean station level) in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->stLPressureGroupValid($stlPressureDecoder, ['3', '/049']));
    }

    public function testSuccessMslPressureGroupValid()
    {
        $mslPressureDecoder = Mockery::mock(MslPressureDecoder::class);
        $mslPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'PPPP']);

        $this->assertTrue($this->validate->mslPressureGroupValid($mslPressureDecoder, ['4', '0101']));
    }

    public function testErrorMslPressureGroupValid()
    {
        $mslPressureDecoder = Mockery::mock(MslPressureDecoder::class);
        $mslPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'PPPP']);
        $mslPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $mslPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean sea level) in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->mslPressureGroupValid($mslPressureDecoder, ['5', '/101']));
    }

    public function testErrorIndicatorMslPressureGroupValid()
    {
        $mslPressureDecoder = Mockery::mock(MslPressureDecoder::class);
        $mslPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'PPPP']);
        $mslPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $mslPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean sea level) in millibars and tenths'
        ]);
        $this->assertFalse($this->validate->mslPressureGroupValid($mslPressureDecoder, ['5', '0101']));
    }

    public function testErrorPressureMslPressureGroupValid()
    {
        $mslPressureDecoder = Mockery::mock(MslPressureDecoder::class);
        $mslPressureDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'PPPP']);
        $mslPressureDecoder->shouldReceive('getIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $mslPressureDecoder->shouldReceive('getFigureAirPressure')->andReturn([
            'PPPP' => 'Last four figures of the air pressure (reduced to mean sea level) in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->mslPressureGroupValid($mslPressureDecoder, ['4', '/101']));
    }

    public function testSuccessBaricTendencyGroupValid()
    {
        $baricTendencyDecoder = Mockery::mock(BaricTendencyDecoder::class);
        $baricTendencyDecoder->shouldReceive('getGroupIndicators')->andReturn(['5', 'a', 'ppp']);

        $this->assertTrue($this->validate->baricTendencyGroupValid($baricTendencyDecoder, ['5', '2', '035']));
    }

    public function testErrorBaricTendencyGroupValid()
    {
        $baricTendencyDecoder = Mockery::mock(BaricTendencyDecoder::class);
        $baricTendencyDecoder->shouldReceive('getGroupIndicators')->andReturn(['5', 'a', 'ppp']);

        $baricTendencyDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['5' => 'Indicator']);
        $baricTendencyDecoder->shouldReceive('getCharacteristicChangeIndicator')->andReturn([
            'a' => 'Characteristic of pressure change'
        ]);
        $baricTendencyDecoder->shouldReceive('getPressureChangeIndicator')->andReturn([
            'ppp' => 'Pressure change over last three hours in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->baricTendencyGroupValid($baricTendencyDecoder, ['4', '\\', '/35']));
    }

    public function testErrorIndicatorBaricTendencyGroupValid()
    {
        $baricTendencyDecoder = Mockery::mock(BaricTendencyDecoder::class);
        $baricTendencyDecoder->shouldReceive('getGroupIndicators')->andReturn(['5', 'a', 'ppp']);
        $baricTendencyDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['5' => 'Indicator']);
        $baricTendencyDecoder->shouldReceive('getCharacteristicChangeIndicator')->andReturn([
            'a' => 'Characteristic of pressure change'
        ]);
        $baricTendencyDecoder->shouldReceive('getPressureChangeIndicator')->andReturn([
            'ppp' => 'Pressure change over last three hours in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->baricTendencyGroupValid($baricTendencyDecoder, ['4', '2', '035']));
    }

    public function testErrorCharacteristicBaricTendencyGroupValid()
    {
        $baricTendencyDecoder = Mockery::mock(BaricTendencyDecoder::class);
        $baricTendencyDecoder->shouldReceive('getGroupIndicators')->andReturn(['5', 'a', 'ppp']);
        $baricTendencyDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['5' => 'Indicator']);
        $baricTendencyDecoder->shouldReceive('getCharacteristicChangeIndicator')->andReturn([
            'a' => 'Characteristic of pressure change'
        ]);
        $baricTendencyDecoder->shouldReceive('getPressureChangeIndicator')->andReturn([
            'ppp' => 'Pressure change over last three hours in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->baricTendencyGroupValid($baricTendencyDecoder, ['5', 'two', '035']));
    }

    public function testErrorPressureChangeBaricTendencyGroupValid()
    {
        $baricTendencyDecoder = Mockery::mock(BaricTendencyDecoder::class);
        $baricTendencyDecoder->shouldReceive('getGroupIndicators')->andReturn(['5', 'a', 'ppp']);
        $baricTendencyDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['5' => 'Indicator']);
        $baricTendencyDecoder->shouldReceive('getCharacteristicChangeIndicator')->andReturn([
            'a' => 'Characteristic of pressure change'
        ]);
        $baricTendencyDecoder->shouldReceive('getPressureChangeIndicator')->andReturn([
            'ppp' => 'Pressure change over last three hours in millibars and tenths'
        ]);

        $this->assertFalse($this->validate->baricTendencyGroupValid($baricTendencyDecoder, ['5', '2', '/35']));
    }

    public function testSuccessAmountRainfallGroupValid()
    {
        $amountRainfallDecoder = Mockery::mock(AmountRainfallDecoder::class);
        $amountRainfallDecoder->shouldReceive('getGroupIndicators')->andReturn(['6', 'RRR', 'tr']);

        $this->assertTrue($this->validate->amountRainfallGroupValid($amountRainfallDecoder, ['6', '001', '2']));
    }

    public function testErrorAmountRainfallGroupValid()
    {
        $amountRainfallDecoder = Mockery::mock(AmountRainfallDecoder::class);
        $amountRainfallDecoder->shouldReceive('getGroupIndicators')->andReturn(['6', 'RRR', 'tr']);
        $amountRainfallDecoder->shouldReceive('getIndicatorGroup')->andReturn(['6' => 'Indicator']);
        $amountRainfallDecoder->shouldReceive('getAmountRainfallIndicator')->andReturn([
            'RRR' => 'Amount of rainfall'
        ]);
        $amountRainfallDecoder->shouldReceive('getDurationPeriodIndicator')->andReturn([
            'tr' => 'Duration period of RRR'
        ]);

        $this->assertFalse($this->validate->amountRainfallGroupValid($amountRainfallDecoder, ['5', '0011', '25']));
    }

    public function testErrorIndicatorAmountRainfallGroupValid()
    {
        $amountRainfallDecoder = Mockery::mock(AmountRainfallDecoder::class);
        $amountRainfallDecoder->shouldReceive('getGroupIndicators')->andReturn(['6', 'RRR', 'tr']);
        $amountRainfallDecoder->shouldReceive('getIndicatorGroup')->andReturn(['6' => 'Indicator']);
        $amountRainfallDecoder->shouldReceive('getAmountRainfallIndicator')->andReturn([
            'RRR' => 'Amount of rainfall'
        ]);
        $amountRainfallDecoder->shouldReceive('getDurationPeriodIndicator')->andReturn([
            'tr' => 'Duration period of RRR'
        ]);

        $this->assertFalse($this->validate->amountRainfallGroupValid($amountRainfallDecoder, ['5', '001', '2']));
    }

    public function testErrorValueAmountRainfallAmountRainfallGroupValid()
    {
        $amountRainfallDecoder = Mockery::mock(AmountRainfallDecoder::class);
        $amountRainfallDecoder->shouldReceive('getGroupIndicators')->andReturn(['6', 'RRR', 'tr']);
        $amountRainfallDecoder->shouldReceive('getIndicatorGroup')->andReturn(['6' => 'Indicator']);
        $amountRainfallDecoder->shouldReceive('getAmountRainfallIndicator')->andReturn([
            'RRR' => 'Amount of rainfall'
        ]);
        $amountRainfallDecoder->shouldReceive('getDurationPeriodIndicator')->andReturn([
            'tr' => 'Duration period of RRR'
        ]);

        $this->assertFalse($this->validate->amountRainfallGroupValid($amountRainfallDecoder, ['6', '/01', '2']));
    }

    public function testErrorDurationPeriodAmountRainfallGroupValid()
    {
        $amountRainfallDecoder = Mockery::mock(AmountRainfallDecoder::class);
        $amountRainfallDecoder->shouldReceive('getGroupIndicators')->andReturn(['6', 'RRR', 'tr']);
        $amountRainfallDecoder->shouldReceive('getIndicatorGroup')->andReturn(['6' => 'Indicator']);
        $amountRainfallDecoder->shouldReceive('getAmountRainfallIndicator')->andReturn([
            'RRR' => 'Amount of rainfall'
        ]);
        $amountRainfallDecoder->shouldReceive('getDurationPeriodIndicator')->andReturn([
            'tr' => 'Duration period of RRR'
        ]);

        $this->assertFalse($this->validate->amountRainfallGroupValid($amountRainfallDecoder, ['6', '001', '\\']));
    }

    public function testSuccessPresentWeatherGroupValid()
    {
        $presentWeatherDecoder = Mockery::mock(PresentWeatherDecoder::class);
        $presentWeatherDecoder->shouldReceive('getGroupIndicators')->andReturn(['7', 'ww', 'W1W2']);

        $this->assertTrue($this->validate->presentWeatherGroupValid($presentWeatherDecoder, ['7', '02', '82']));
    }

    public function testErrorPresentWeatherGroupValid()
    {
        $presentWeatherDecoder = Mockery::mock(PresentWeatherDecoder::class);
        $presentWeatherDecoder->shouldReceive('getGroupIndicators')->andReturn(['7', 'ww', 'W1W2']);
        $presentWeatherDecoder->shouldReceive('getIndicatorGroup')->andReturn(['7' => 'Indicator']);
        $presentWeatherDecoder->shouldReceive('getPresentWeatherIndicator')->andReturn(['ww' => 'Present weather']);
        $presentWeatherDecoder->shouldReceive('getPastWeatherIndicator')->andReturn(['W1W2' => 'Past weather']);

        $this->assertFalse($this->validate->presentWeatherGroupValid($presentWeatherDecoder, ['8', '022', '822']));
    }

    public function testErrorIndicatorPresentWeatherGroupValid()
    {
        $presentWeatherDecoder = Mockery::mock(PresentWeatherDecoder::class);
        $presentWeatherDecoder->shouldReceive('getGroupIndicators')->andReturn(['7', 'ww', 'W1W2']);
        $presentWeatherDecoder->shouldReceive('getIndicatorGroup')->andReturn(['7' => 'Indicator']);
        $presentWeatherDecoder->shouldReceive('getPresentWeatherIndicator')->andReturn(['ww' => 'Present weather']);
        $presentWeatherDecoder->shouldReceive('getPastWeatherIndicator')->andReturn(['W1W2' => 'Past weather']);

        $this->assertFalse($this->validate->presentWeatherGroupValid($presentWeatherDecoder, ['8', '02', '82']));
    }

    public function testErrorPresentWeatherPresentWeatherGroupValid()
    {
        $presentWeatherDecoder = Mockery::mock(PresentWeatherDecoder::class);
        $presentWeatherDecoder->shouldReceive('getGroupIndicators')->andReturn(['7', 'ww', 'W1W2']);
        $presentWeatherDecoder->shouldReceive('getIndicatorGroup')->andReturn(['7' => 'Indicator']);
        $presentWeatherDecoder->shouldReceive('getPresentWeatherIndicator')->andReturn(['ww' => 'Present weather']);
        $presentWeatherDecoder->shouldReceive('getPastWeatherIndicator')->andReturn(['W1W2' => 'Past weather']);

        $this->assertFalse($this->validate->presentWeatherGroupValid($presentWeatherDecoder, ['7', 'ICE', '82']));
    }

    //TODO Analyse
    public function testErrorPastWeatherPresentWeatherGroupValid()
    {
        $presentWeatherDecoder = Mockery::mock(PresentWeatherDecoder::class);
        $presentWeatherDecoder->shouldReceive('getGroupIndicators')->andReturn(['7', 'ww', 'W1W2']);
        $presentWeatherDecoder->shouldReceive('getIndicatorGroup')->andReturn(['7' => 'Indicator']);
        $presentWeatherDecoder->shouldReceive('getPresentWeatherIndicator')->andReturn(['ww' => 'Present weather']);
        $presentWeatherDecoder->shouldReceive('getPastWeatherIndicator')->andReturn(['W1W2' => 'Past weather']);

        $this->assertFalse($this->validate->presentWeatherGroupValid($presentWeatherDecoder, ['7', '02', '2']));
    }

    public function testSuccessCloudPresentGroupValid()
    {
        $cloudPresentDecoder = Mockery::mock(CloudPresentDecoder::class);
        $cloudPresentDecoder->shouldReceive('getGroupIndicators')->andReturn(['8', 'Nh', 'Cl', 'Cm', 'Ch']);

        $this->assertTrue($this->validate->cloudPresentGroupValid($cloudPresentDecoder, ['8', '2', '5', '5', '/']));
    }

    public function testErrorCloudPresentGroupValid()
    {
        $cloudPresentDecoder = Mockery::mock(CloudPresentDecoder::class);
        $cloudPresentDecoder->shouldReceive('getGroupIndicators')->andReturn(['8', 'Nh', 'Cl', 'Cm', 'Ch']);
        $cloudPresentDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $cloudPresentDecoder->shouldReceive('getAmountCloudIndicator')->andReturn([
            'Nh' => 'Amount of low cloud or medium cloud if no low cloud present'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormLowCloudIndicator')->andReturn([
            'Cl' => 'Form of low cloud'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormMediumCloudIndicator')->andReturn([
            'Cm' => 'Form of medium cloud'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormHighCloudIndicator')->andReturn([
            'Ch' => 'Form of high cloud'
        ]);

        $this->assertFalse($this->validate->cloudPresentGroupValid($cloudPresentDecoder, ['88', '22', '55', '55', '//']));
    }

    public function testErrorIndicatorCloudPresentGroupValid()
    {
        $cloudPresentDecoder = Mockery::mock(CloudPresentDecoder::class);
        $cloudPresentDecoder->shouldReceive('getGroupIndicators')->andReturn(['8', 'Nh', 'Cl', 'Cm', 'Ch']);
        $cloudPresentDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $cloudPresentDecoder->shouldReceive('getAmountCloudIndicator')->andReturn([
            'Nh' => 'Amount of low cloud or medium cloud if no low cloud present'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormLowCloudIndicator')->andReturn([
            'Cl' => 'Form of low cloud'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormMediumCloudIndicator')->andReturn([
            'Cm' => 'Form of medium cloud'
        ]);
        $cloudPresentDecoder->shouldReceive('getFormHighCloudIndicator')->andReturn([
            'Ch' => 'Form of high cloud'
        ]);

        $this->assertFalse($this->validate->cloudPresentGroupValid($cloudPresentDecoder, ['88', '2', '5', '5', '/']));
    }

    public function testSuccessGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);

        $this->assertTrue($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['3', '4', '0', '08']));
    }

    public function testErrorGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);
        $groundWithoutSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $groundWithoutSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground without snow or measurable ice cover'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getSignTemperatureIndicator')->andReturn([
            'Sn' => 'Sign of temperature'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getMinimumTemperature')->andReturn([
            'TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'
        ]);

        $this->assertFalse($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['33', '44', '00', '088']));
    }

    public function testErrorIndicatorGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);
        $groundWithoutSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $groundWithoutSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground without snow or measurable ice cover'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getSignTemperatureIndicator')->andReturn([
            'Sn' => 'Sign of temperature'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getMinimumTemperature')->andReturn([
            'TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'
        ]);

        $this->assertFalse($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['33', '4', '0', '08']));
    }

    public function testErrorStateGroundGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);
        $groundWithoutSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $groundWithoutSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground without snow or measurable ice cover'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getSignTemperatureIndicator')->andReturn([
            'Sn' => 'Sign of temperature'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getMinimumTemperature')->andReturn([
            'TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'
        ]);

        $this->assertFalse($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['3', '44', '0', '08']));
    }

    public function testErrorSignTemperatureGroundGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);
        $groundWithoutSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $groundWithoutSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground without snow or measurable ice cover'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getSignTemperatureIndicator')->andReturn([
            'Sn' => 'Sign of temperature'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getMinimumTemperature')->andReturn([
            'TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'
        ]);

        $this->assertFalse($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['3', '4', '2', '08']));
    }

    public function testErrorMinimumTemperatureGroundGroundWithoutSnowGroupValid()
    {
        $groundWithoutSnowDecoder = Mockery::mock(GroundWithoutSnowDecoder::class);
        $groundWithoutSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['3', 'E', 'Sn', 'TgTg']);
        $groundWithoutSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['3' => 'Indicator']);
        $groundWithoutSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground without snow or measurable ice cover'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getSignTemperatureIndicator')->andReturn([
            'Sn' => 'Sign of temperature'
        ]);
        $groundWithoutSnowDecoder->shouldReceive('getMinimumTemperature')->andReturn([
            'TgTg' => 'Grass minimum temperature (rounded to nearest whole degree)'
        ]);

        $this->assertFalse($this->validate->groundWithoutSnowGroupValid($groundWithoutSnowDecoder, ['3', '4', '0', '088']));
    }

    public function testSuccessGroundWithSnowGroupValid()
    {
        $groundWithSnowDecoder = Mockery::mock(GroundWithSnowDecoder::class);
        $groundWithSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'E', 'sss']);

        $this->assertTrue($this->validate->groundWithSnowGroupValid($groundWithSnowDecoder, ['4', '9', '998']));
    }

    public function testErrorGroundWithSnowGroupValid()
    {
        $groundWithSnowDecoder = Mockery::mock(GroundWithSnowDecoder::class);
        $groundWithSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'E', 'sss']);
        $groundWithSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $groundWithSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground with snow or measurable ice cover'
        ]);
        $groundWithSnowDecoder->shouldReceive('getDepthSnowIndicator')->andReturn(['sss' => 'Depth of snow']);

        $this->assertFalse($this->validate->groundWithSnowGroupValid($groundWithSnowDecoder, ['44', '99', '9981']));
    }

    public function testErrorIndicatorGroundWithSnowGroupValid()
    {
        $groundWithSnowDecoder = Mockery::mock(GroundWithSnowDecoder::class);
        $groundWithSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'E', 'sss']);
        $groundWithSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $groundWithSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground with snow or measurable ice cover'
        ]);
        $groundWithSnowDecoder->shouldReceive('getDepthSnowIndicator')->andReturn(['sss' => 'Depth of snow']);

        $this->assertFalse($this->validate->groundWithSnowGroupValid($groundWithSnowDecoder, ['44', '9', '998']));
    }

    public function testErrorStateGroundGroundWithSnowGroupValid()
    {
        $groundWithSnowDecoder = Mockery::mock(GroundWithSnowDecoder::class);
        $groundWithSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'E', 'sss']);
        $groundWithSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $groundWithSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground with snow or measurable ice cover'
        ]);
        $groundWithSnowDecoder->shouldReceive('getDepthSnowIndicator')->andReturn(['sss' => 'Depth of snow']);

        $this->assertFalse($this->validate->groundWithSnowGroupValid($groundWithSnowDecoder, ['4', '99', '998']));
    }

    public function testErrorDepthSnowGroundWithSnowGroupValid()
    {
        $groundWithSnowDecoder = Mockery::mock(GroundWithSnowDecoder::class);
        $groundWithSnowDecoder->shouldReceive('getGroupIndicators')->andReturn(['4', 'E', 'sss']);
        $groundWithSnowDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['4' => 'Indicator']);
        $groundWithSnowDecoder->shouldReceive('getStateGroundIndicator')->andReturn([
            'E' => 'State of ground with snow or measurable ice cover'
        ]);
        $groundWithSnowDecoder->shouldReceive('getDepthSnowIndicator')->andReturn(['sss' => 'Depth of snow']);

        $this->assertFalse($this->validate->groundWithSnowGroupValid($groundWithSnowDecoder, ['4', '9', '99']));
    }

    public function testSuccessSunshineRadiationDataGroupValid()
    {
        $sunshineRadiationDataDecoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $sunshineRadiationDataDecoder->shouldReceive('getGroupIndicators')->andReturn(['55', 'SSS']);

        $this->assertTrue($this->validate->sunshineRadiationDataGroupValid($sunshineRadiationDataDecoder, ['55', '118']));
    }

    public function testErrorSunshineRadiationDataGroupValid()
    {
        $sunshineRadiationDataDecoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $sunshineRadiationDataDecoder->shouldReceive('getGroupIndicators')->andReturn(['55', 'SSS']);
        $sunshineRadiationDataDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['55' => 'Indicator']);
        $sunshineRadiationDataDecoder->shouldReceive('getDurationTinderIndicator')->andReturn([
            'SSS' => 'Duration of daily sunshine'
        ]);

        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid($sunshineRadiationDataDecoder, ['5', '11']));
    }

    public function testErrorIndicatorSunshineRadiationDataGroupValid()
    {
        $sunshineRadiationDataDecoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $sunshineRadiationDataDecoder->shouldReceive('getGroupIndicators')->andReturn(['55', 'SSS']);
        $sunshineRadiationDataDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['55' => 'Indicator']);
        $sunshineRadiationDataDecoder->shouldReceive('getDurationTinderIndicator')->andReturn([
            'SSS' => 'Duration of daily sunshine'
        ]);

        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid($sunshineRadiationDataDecoder, ['5', '118']));
    }

    public function testErrorDurationSunshineSunshineRadiationDataGroupValid()
    {
        $sunshineRadiationDataDecoder = Mockery::mock(SunshineRadiationDataDecoder::class);
        $sunshineRadiationDataDecoder->shouldReceive('getGroupIndicators')->andReturn(['55', 'SSS']);
        $sunshineRadiationDataDecoder->shouldReceive('getGetIndicatorGroup')->andReturn(['55' => 'Indicator']);
        $sunshineRadiationDataDecoder->shouldReceive('getDurationTinderIndicator')->andReturn([
            'SSS' => 'Duration of daily sunshine'
        ]);

        $this->assertFalse($this->validate->sunshineRadiationDataGroupValid($sunshineRadiationDataDecoder, ['55', '11']));
    }

    public function testSuccessAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);

        $this->assertTrue($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['8', '8', '5', '18']));
    }

    public function testErrorAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);
        $additionalCloudInformation->shouldReceive('getIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $additionalCloudInformation->shouldReceive('getAmountCloudLayerIndicator')->andReturn([
            'Ns' => 'Amount of individual cloud layer'
        ]);
        $additionalCloudInformation->shouldReceive('getFormCloudIndicator')->andReturn([
            'C' => 'Form of cloud'
        ]);
        $additionalCloudInformation->shouldReceive('getHeightCloudIndicator')->andReturn([
            'hshs' => 'Height of base cloud layer'
        ]);

        $this->assertFalse($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['88', '89', '59', '189']));
    }

    public function testErrorIndicatorAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);
        $additionalCloudInformation->shouldReceive('getIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $additionalCloudInformation->shouldReceive('getAmountCloudLayerIndicator')->andReturn([
            'Ns' => 'Amount of individual cloud layer'
        ]);
        $additionalCloudInformation->shouldReceive('getFormCloudIndicator')->andReturn([
            'C' => 'Form of cloud'
        ]);
        $additionalCloudInformation->shouldReceive('getHeightCloudIndicator')->andReturn([
            'hshs' => 'Height of base cloud layer'
        ]);

        $this->assertFalse($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['88', '8', '5', '18']));
    }

    public function testErrorAmountIndividualCloudAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);
        $additionalCloudInformation->shouldReceive('getIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $additionalCloudInformation->shouldReceive('getAmountCloudLayerIndicator')->andReturn([
            'Ns' => 'Amount of individual cloud layer'
        ]);
        $additionalCloudInformation->shouldReceive('getFormCloudIndicator')->andReturn([
            'C' => 'Form of cloud'
        ]);
        $additionalCloudInformation->shouldReceive('getHeightCloudIndicator')->andReturn([
            'hshs' => 'Height of base cloud layer'
        ]);

        $this->assertFalse($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['8', '89', '5', '18']));
    }

    public function testErrorFormCloudAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);
        $additionalCloudInformation->shouldReceive('getIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $additionalCloudInformation->shouldReceive('getAmountCloudLayerIndicator')->andReturn([
            'Ns' => 'Amount of individual cloud layer'
        ]);
        $additionalCloudInformation->shouldReceive('getFormCloudIndicator')->andReturn([
            'C' => 'Form of cloud'
        ]);
        $additionalCloudInformation->shouldReceive('getHeightCloudIndicator')->andReturn([
            'hshs' => 'Height of base cloud layer'
        ]);

        $this->assertFalse($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['8', '8', '55', '18']));
    }

    public function testErrorHeightBaseAdditionalCloudInformationGroupValid()
    {
        $additionalCloudInformation = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $additionalCloudInformation->shouldReceive('getGroupIndicators')->andReturn(['8', 'Ns', 'C', 'hshs']);
        $additionalCloudInformation->shouldReceive('getIndicatorGroup')->andReturn(['8' => 'Indicator']);
        $additionalCloudInformation->shouldReceive('getAmountCloudLayerIndicator')->andReturn([
            'Ns' => 'Amount of individual cloud layer'
        ]);
        $additionalCloudInformation->shouldReceive('getFormCloudIndicator')->andReturn([
            'C' => 'Form of cloud'
        ]);
        $additionalCloudInformation->shouldReceive('getHeightCloudIndicator')->andReturn([
            'hshs' => 'Height of base cloud layer'
        ]);

        $this->assertFalse($this->validate->additionalCloudInformationGroupValid($additionalCloudInformation, ['8', '8', '5', '189']));
    }

    public function testSuccessEmpryGetErrorsWithGroups()
    {
        $this->assertEquals([], $this->validate->getErrorsWithGroups());
    }

    public function testSuccessNotEmpryGetErrorsWithGroups()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertEquals(['AACC' => 'The summary type group data does not match the specified format; Code group - AACC'], $this->validate->getErrorsWithGroups());
    }

    public function testSuccessIsArrayGetErrorsWithGroups()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertIsArray($this->validate->getErrorsWithGroups());
    }

    public function testSuccessGetErrors()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, [
            'AAXX/BBXX' => [
                'description' => 'Synoptic Code Identifier',
                'code' => 'AACC',
                'error' => 'The summary type group data does not match the specified format; Code group - AACC'
            ]
        ]);

        $expected = [0 => [
            'indicator_group' => 'AAXX/BBXX',
            'description_indicator' => 'Synoptic Code Identifier',
            'code' => 'AACC',
            'description_error' => 'The summary type group data does not match the specified format; Code group - AACC',
        ]];

        $this->assertEquals($expected, $this->validate->getErrors());
    }

    public function testSuccessEmptyErrorsGetErrors()
    {
        $this->assertEquals([], $this->validate->getErrors());
    }

    public function testSuccessIsArrayGetErrors()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, [
            'AAXX/BBXX' => [
                'description' => 'Synoptic Code Identifier',
                'code' => 'AACC',
                'error' => 'The summary type group data does not match the specified format; Code group - AACC'
            ]
        ]);

        $this->assertIsArray($this->validate->getErrors());
    }

    public function testSuccessGetErrorByGroup()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $expected = 'The summary type group data does not match the specified format; Code group - AACC';

        $this->assertEquals($expected, $this->validate->getErrorByGroup('AACC'));
    }

    public function testSuccessIsStringGetErrorByGroup()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertIsString($this->validate->getErrorByGroup('AACC'));
    }

    public function testSuccessTrueNotExistsError()
    {
        $this->assertTrue($this->validate->notExistsError(['AAXX']));
    }

    public function testSuccessFalseNotExistsError()
    {
        $property = new \ReflectionProperty(Validate::class, 'errors');
        $property->setAccessible(true);
        $property->setValue($this->validate, ['AACC' => 'The summary type group data does not match the specified format; Code group - AACC']);

        $this->assertFalse($this->validate->notExistsError(['AACC']));
    }

    public function testSuccessSetError()
    {
        $reflector = new \ReflectionClass(Validate::class);
        $method = $reflector->getMethod('setError');
        $method->setAccessible(true);
        $method->invokeArgs($this->validate, [
            'AAXX/BBXX', 'Synoptic Code Identifier', 'AACC',
            'The summary type group data does not match the specified format; Code group - AACC'
        ]);

        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $expected = [
            'AAXX/BBXX' => [
                'description' => 'Synoptic Code Identifier',
                'code' => 'AACC',
                'error' => 'The summary type group data does not match the specified format; Code group - AACC'
            ]
        ];

        $this->assertEquals($expected, $value);
    }

    public function testSuccessIsArraySetError()
    {
        $reflector = new \ReflectionClass(Validate::class);
        $method = $reflector->getMethod('setError');
        $method->setAccessible(true);
        $method->invokeArgs($this->validate, [
            'AAXX/BBXX', 'Synoptic Code Identifier', 'AACC',
            'The summary type group data does not match the specified format; Code group - AACC'
        ]);

        $property = $reflector->getProperty('errors');
        $property->setAccessible(true);
        $value = $property->getValue($this->validate);

        $this->assertIsArray($value);
    }

    public function testSuccessPreparation()
    {
        $raw = 'AAXX 07181  33837 11583 83102 10039 21007 30049    40101 52035 60012 70282  8255/ 333 10091 555 1/004=';
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $this->assertEquals($expected, $this->validate->preparation($raw));
    }

    public function testSuccessIsStringPreparation()
    {
        $raw = 'AAXX 07181  33837 11583 83102 10039 21007 30049    40101 52035 60012 70282  8255/ 333 10091 555 1/004=';

        $this->assertIsString($this->validate->preparation($raw));
    }

    public function testSuccessIsValid()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertTrue($this->validate->isValid());
    }

    public function testExceptionIsValid()
    {
        $report = null;

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->expectException(\Exception::class);

        $this->validate->isValid();
    }

    public function testErrorEndEqualSignIsValid()
    {
        $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertFalse($this->validate->isValid());
    }

    public function testErrorCountSymbolIsValid()
    {
        $report = 'AAXX 0718 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

        $property = new \ReflectionProperty(Validate::class, 'report');
        $property->setAccessible(true);
        $property->setValue($this->validate, $report);

        $this->assertFalse($this->validate->isValid());
    }

    public function testSuccessIsValidGroup()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getStationAreaIndicator')->andReturn(['II' => 'Area station']);
        $indexDecoder->shouldReceive('getStationIndexIndicator')->andReturn(['iii' => 'Station index']);
        $indexDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['II', 'iii']);

        $this->assertTrue($this->validate->isValidGroup($indexDecoder, ['33', '837']));
    }

    public function testErrorIsValidGroup()
    {
        $indexDecoder = Mockery::mock(IndexDecoder::class);
        $indexDecoder->shouldReceive('getStationAreaIndicator')->andReturn(['II' => 'Area station']);
        $indexDecoder->shouldReceive('getStationIndexIndicator')->andReturn(['iii' => 'Station index']);
        $indexDecoder->shouldReceive('getGroupIndicators')->once()->andReturn(['II', 'iii']);

        $this->assertFalse($this->validate->isValidGroup($indexDecoder, ['3', '837']));
    }
}

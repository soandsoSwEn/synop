<?php

namespace Soandso\Synop\Tests\Fabrication;

use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AirTemperatureDecoder;
use Soandso\Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Sheme\AdditionalCloudInformationGroup;
use Soandso\Synop\Sheme\AirTemperatureGroup;
use Soandso\Synop\Sheme\AmountRainfallGroup;
use Soandso\Synop\Sheme\BaricTendencyGroup;
use Soandso\Synop\Sheme\CloudWindGroup;
use Soandso\Synop\Sheme\DewPointTemperatureGroup;
use Soandso\Synop\Sheme\GroundWithoutSnowGroup;
use Soandso\Synop\Sheme\GroundWithSnowGroup;
use Soandso\Synop\Sheme\LowCloudVisibilityGroup;
use Soandso\Synop\Sheme\MaxAirTemperatureGroup;
use Soandso\Synop\Sheme\MinAirTemperatureGroup;
use Soandso\Synop\Sheme\MslPressureGroup;
use Soandso\Synop\Sheme\RegionalExchangeAmountRainfallGroup;
use Soandso\Synop\Sheme\StLPressureGroup;
use Soandso\Synop\Sheme\SunshineRadiationDataGroup;

class UnitTest extends TestCase
{
    private $unit;

    private $defaultUnits = [
        LowCloudVisibilityGroup::class => ['h' => 'm', 'VV' => 'km'],
        CloudWindGroup::class => ['dd' => 'degrees', 'ff' => 'm/s'],
        AirTemperatureGroup::class => ['TTT' => 'degree C'],
        DewPointTemperatureGroup::class => ['TdTdTd' => 'degree C'],
        StLPressureGroup::class => ['PoPoPoPo' => 'hPa'],
        MslPressureGroup::class => ['PPPP' => 'hPa'],
        BaricTendencyGroup::class => ['ppp' => 'hPa'],
        AmountRainfallGroup::class => ['RRR' => 'mm'],
        MaxAirTemperatureGroup::class => ['TxTxTx' => 'degree C'],
        MinAirTemperatureGroup::class => ['TnTnTn' => 'degree C'],
        GroundWithoutSnowGroup::class => ['TgTg' => 'degree C'],
        GroundWithSnowGroup::class => ['sss' => 'cm'],
        SunshineRadiationDataGroup::class => ['SSS' => 'hour'],
        RegionalExchangeAmountRainfallGroup::class => ['RRR' => 'mm'],
        AdditionalCloudInformationGroup::class => ['hshs' => 'm'],
    ];

    protected function setUp(): void
    {
        $this->unit = new Unit();
    }

    protected function tearDown(): void
    {
        unset($this->unit);
    }

    public function testSuccessGetDefaultUnits()
    {
        $reflector = new \ReflectionClass(Unit::class);
        $method = $reflector->getMethod('getDefaultUnits');
        $method->setAccessible(true);
        $result = $method->invoke($this->unit);

        $this->assertEquals($this->defaultUnits, $result);
    }

    public function testSuccessSetUnit()
    {
        $this->unit->setUnit(CloudWindGroup::class, 'ff', 'km/h');

        $expected = [
            LowCloudVisibilityGroup::class => ['h' => 'm', 'VV' => 'km'],
            CloudWindGroup::class => ['dd' => 'degrees', 'ff' => 'km/h'],
            AirTemperatureGroup::class => ['TTT' => 'degree C'],
            DewPointTemperatureGroup::class => ['TdTdTd' => 'degree C'],
            StLPressureGroup::class => ['PoPoPoPo' => 'hPa'],
            MslPressureGroup::class => ['PPPP' => 'hPa'],
            BaricTendencyGroup::class => ['ppp' => 'hPa'],
            AmountRainfallGroup::class => ['RRR' => 'mm'],
            MaxAirTemperatureGroup::class => ['TxTxTx' => 'degree C'],
            MinAirTemperatureGroup::class => ['TnTnTn' => 'degree C'],
            GroundWithoutSnowGroup::class => ['TgTg' => 'degree C'],
            GroundWithSnowGroup::class => ['sss' => 'cm'],
            SunshineRadiationDataGroup::class => ['SSS' => 'hour'],
            RegionalExchangeAmountRainfallGroup::class => ['RRR' => 'mm'],
            AdditionalCloudInformationGroup::class => ['hshs' => 'm'],
        ];

        $reflector = new \ReflectionClass(Unit::class);
        $property = $reflector->getProperty('defaultUnits');
        $property->setAccessible(true);
        $value = $property->getValue($this->unit);

        $this->assertEquals($expected, $value);
    }

    public function testExceptionSetUnit()
    {
        $this->expectException(\Exception::class);

        $this->unit->setUnit(CloudWindDecoder::class, 'ff', 'km/h');
    }

    public function testSuccessGetUnitByGroup()
    {
        $expected = ['TxTxTx' => 'degree C'];

        $this->assertEquals($expected, $this->unit->getUnitByGroup(MaxAirTemperatureGroup::class));
    }

    public function testSuccessSetNewValueGetUnitByGroup()
    {
        $expected = ['TxTxTx' => 'degree K'];

        $this->unit->setUnit(MaxAirTemperatureGroup::class, 'TxTxTx', 'degree K');

        $this->assertEquals($expected, $this->unit->getUnitByGroup(MaxAirTemperatureGroup::class));
    }

    public function testNullGetUnitByGroup()
    {
        $this->assertNull($this->unit->getUnitByGroup(AirTemperatureDecoder::class));
    }
}

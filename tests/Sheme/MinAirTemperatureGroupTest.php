<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\MinAirTemperatureGroup;

class MinAirTemperatureGroupTest extends TestCase
{
    private $minAirTemperatureGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->minAirTemperatureGroup = new MinAirTemperatureGroup('21021', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->minAirTemperatureGroup);
        Mockery::close();
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->minAirTemperatureGroup->setData('21051', $validate);

        $this->assertEquals('21051', $this->minAirTemperatureGroup->getRawAirTemperature());
    }

    public function testErrorSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->minAirTemperatureGroup->setData('21051', $validate);

        $this->assertNotEquals('21031', $this->minAirTemperatureGroup->getRawAirTemperature());
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->minAirTemperatureGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('2SnTnTnTn', $this->minAirTemperatureGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->minAirTemperatureGroup->getGroupIndicator());
    }
}

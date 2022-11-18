<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\MaxAirTemperatureGroup;

class MaxAirTemperatureGroupTest extends TestCase
{
    private $maxAirTemperatureGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->maxAirTemperatureGroup = new MaxAirTemperatureGroup('10091', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->maxAirTemperatureGroup);
        Mockery::close();
    }

    public function testSuccesSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->maxAirTemperatureGroup->setData('10105', $validate);

        $this->assertEquals('10105', $this->maxAirTemperatureGroup->getRawAirTemperature());
    }

    public function testErrorSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->maxAirTemperatureGroup->setData('10105', $validate);

        $this->assertNotEquals('10091', $this->maxAirTemperatureGroup->getRawAirTemperature());
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->maxAirTemperatureGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('1SnTxTxTx', $this->maxAirTemperatureGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->maxAirTemperatureGroup->getGroupIndicator());
    }
}

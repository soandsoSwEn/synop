<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\RegionalExchangeAmountRainfallGroup;

class RegionalExchangeAmountRainfallGroupTest extends TestCase
{
    private $regionalExchangeAmountRainfallGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);
        $this->regionalExchangeAmountRainfallGroup = new RegionalExchangeAmountRainfallGroup('60012', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->regionalExchangeAmountRainfallGroup);
        Mockery::close();
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->regionalExchangeAmountRainfallGroup->setData('69952', $validate);

        $this->assertEquals('69952', $this->regionalExchangeAmountRainfallGroup->getRawAmountRainfall());
    }

    public function testErrorSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->regionalExchangeAmountRainfallGroup->setData('69952', $validate);

        $this->assertNotEquals('60012', $this->regionalExchangeAmountRainfallGroup->getRawAmountRainfall());
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $this->expectException(\Exception::class);

        $this->regionalExchangeAmountRainfallGroup->setData('', $validate);
    }
}
<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\UnitInterface;
use Soandso\Synop\Sheme\BaseGroupWithUnits;

class BaseGroupWithUnitsTest extends TestCase
{
    private $baseGroupWithUnits;

    protected function setUp(): void
    {
        $this->baseGroupWithUnits = new BaseGroupWithUnits();
    }

    protected function tearDown(): void
    {
        unset($this->baseGroupWithUnits);
    }

    public function testSuccessSetUnit()
    {
        $unit = Mockery::mock(Unit::class);

        $this->baseGroupWithUnits->setUnit($unit);

        $reflector = new \ReflectionClass(BaseGroupWithUnits::class);
        $property = $reflector->getProperty('unit');
        $property->setAccessible(true);
        $value = $property->getValue($this->baseGroupWithUnits);

        $this->assertInstanceOf(UnitInterface::class, $value);
    }

    public function testSuccessGetUnit()
    {
        $unit = Mockery::mock(Unit::class);
        $this->baseGroupWithUnits->setUnit($unit);

        $this->assertInstanceOf(UnitInterface::class, $this->baseGroupWithUnits->getUnit());
    }

    public function testSuccessGetUnitValue()
    {
        $unit = Mockery::mock(Unit::class);
        $unit->shouldReceive('getUnitByGroup')->once()->andReturn(['h' => 'm', 'VV' => 'km']);

        $this->baseGroupWithUnits->setUnit($unit);

        $this->assertEquals(['h' => 'm', 'VV' => 'km'], $this->baseGroupWithUnits->getUnitValue());
    }

    public function testNullGetUnitValue()
    {
        $unit = Mockery::mock(Unit::class);
        $unit->shouldReceive('getUnitByGroup')->once()->andReturn(null);

        $this->baseGroupWithUnits->setUnit($unit);

        $this->assertNull($this->baseGroupWithUnits->getUnitValue());
    }
}

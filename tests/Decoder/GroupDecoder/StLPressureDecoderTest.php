<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder;
use Soandso\Synop\Fabrication\Validate;

class StLPressureDecoderTest extends TestCase
{
    private $stlPressure;

    protected function setUp(): void
    {
        $this->stlPressure = new StLPressureDecoder('30049');
    }

    protected function tearDown(): void
    {
        unset($this->stlPressure);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->stlPressure);

        $this->assertEquals('3', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->stlPressure);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $stlPressure = new StLPressureDecoder('40101');
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($stlPressure);

        $this->assertNotEquals('3', $result);
    }

    public function testSuccessGetCodeFigurePressure()
    {
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($this->stlPressure);

        $this->assertEquals('0049', $result);
    }

    public function testSuccessIsStringGetCodeFigurePressure()
    {
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($this->stlPressure);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigurePressure()
    {
        $stlPressure = new StLPressureDecoder('40101');
        $reflector = new \ReflectionClass(StLPressureDecoder::class);
        $method = $reflector->getMethod('getCodeFigurePressure');
        $method->setAccessible(true);
        $result = $method->invoke($stlPressure);

        $this->assertNotEquals('0049', $result);
    }

    public function testSuccessGetStLPressure()
    {
        $this->assertEquals(1004.9, $this->stlPressure->getStLPressure());
    }

    public function testSuccessIsFloatGetStLPressure()
    {
        $this->assertIsFloat($this->stlPressure->getStLPressure());
    }

    public function testErrorGetStLPressure()
    {
        $this->assertNotEquals(998.5, $this->stlPressure->getStLPressure());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->stlPressure->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $stlPressure = new StLPressureDecoder('40101');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->withArgs(['Soandso\Synop\Decoder\GroupDecoder\StLPressureDecoder', '3', '0049'])->andReturn(true);

        $this->assertFalse($stlPressure->isGroup($validate));
    }
}

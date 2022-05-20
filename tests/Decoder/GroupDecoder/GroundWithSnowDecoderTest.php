<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\GroundWithSnowDecoder;
use Soandso\Synop\Fabrication\Validate;

class GroundWithSnowDecoderTest extends TestCase
{
    private $groundWithSnow;

    protected function setUp(): void
    {
        $this->groundWithSnow = new GroundWithSnowDecoder('49998');
    }

    protected function tearDown(): void
    {
        unset($this->groundWithSnow);
        Mockery::close();
    }

    public function testSuccessgetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals($result, '4');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals($result, '4');
    }

    public function testSuccessGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals($result, '9');
    }

    public function testErrorGetCodeFigureStateGround()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals($result, '9');
    }

    public function testSuccessGetCodeFigureDepthSnow()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDepthSnow');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals($result, '998');
    }

    public function testErrorGetCodeFigureDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDepthSnow');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals($result, '998');
    }

    public function testSuccessGetCodeGroundState()
    {
        $this->assertEquals($this->groundWithSnow->getCodeGroundState(), 9);
    }

    public function testErrorGetCodeGroundState()
    {
        $this->assertNotEquals($this->groundWithSnow->getCodeGroundState(), 'nine');
    }

    public function testExceptionGetCodeGroundState()
    {
        $groundWithSnow = new GroundWithSnowDecoder('AAXX');
        $this->expectException(\Exception::class);

        $groundWithSnow->getCodeGroundState();
    }

    public function testSuccessGetGroundState()
    {
        $this->assertEquals($this->groundWithSnow->getGroundState(), 'Snow covering ground completely; deep drifts');
    }

    public function testErrorGetGroundState()
    {
        $this->assertNotEquals($this->groundWithSnow->getGroundState(), 'Loose dry snow covering less than one-half of the ground');
    }

    public function testExceptionGetGroundState()
    {
        $groundWithSnow = new GroundWithSnowDecoder('AAXX');
        $this->expectException(\Exception::class);

        $groundWithSnow->getGroundState();
    }

    public function testSuccessStringGetDepthSnow()
    {
        $this->assertEquals($this->groundWithSnow->getDepthSnow(), ['value' => 'Snow cover, not continuous']);
    }

    public function testErrorStringGetDepthSnow()
    {
        $this->assertNotEquals($this->groundWithSnow->getDepthSnow(), ['value' => 'Measurement impossible or inaccurate']);
    }

    public function testSuccessIntegerGetDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('49025');
        $this->assertEquals($groundWithSnow->getDepthSnow(), ['value' => 25]);
    }

    public function testErrorIntegerGetDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('49020');
        $this->assertNotEquals($groundWithSnow->getDepthSnow(), ['value' => 25]);
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->groundWithSnow->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $groundWithSnow = new GroundWithSnowDecoder('AAXX');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($groundWithSnow->isGroup($validate));
    }
}
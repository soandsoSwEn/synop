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

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals('4', $result);
    }

    public function testSuccessIsStringGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals('4', $result);
    }

    public function testSuccessGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals('9', $result);
    }

    public function testSuccessIsStringGetCodeFigureStateGround()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals('9', $result);
    }

    public function testErrorGetCodeFigureStateGround()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureStateGround');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals('9', $result);
    }

    public function testSuccessGetCodeFigureDepthSnow()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDepthSnow');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertEquals('998', $result);
    }

    public function testSuccessIsStringGetCodeFigureDepthSnow()
    {
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDepthSnow');
        $method->setAccessible(true);
        $result = $method->invoke($this->groundWithSnow);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('10091');
        $reflector = new \ReflectionClass(GroundWithSnowDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDepthSnow');
        $method->setAccessible(true);
        $result = $method->invoke($groundWithSnow);

        $this->assertNotEquals('998', $result);
    }

    public function testSuccessGetCodeGroundState()
    {
        $this->assertEquals(9, $this->groundWithSnow->getCodeGroundState());
    }

    public function testSuccessIsIntGetCodeGroundState()
    {
        $this->assertIsInt($this->groundWithSnow->getCodeGroundState());
    }

    public function testErrorGetCodeGroundState()
    {
        $this->assertNotEquals('nine', $this->groundWithSnow->getCodeGroundState());
    }

    public function testExceptionGetCodeGroundState()
    {
        $groundWithSnow = new GroundWithSnowDecoder('AAXX');
        $this->expectException(\Exception::class);

        $groundWithSnow->getCodeGroundState();
    }

    public function testSuccessGetGroundState()
    {
        $this->assertEquals('Snow covering ground completely; deep drifts', $this->groundWithSnow->getGroundState());
    }

    public function testSuccessIsStringGetGroundState()
    {
        $this->assertIsString($this->groundWithSnow->getGroundState());
    }

    public function testErrorGetGroundState()
    {
        $this->assertNotEquals(
            'Loose dry snow covering less than one-half of the ground',
            $this->groundWithSnow->getGroundState()
        );
    }

    public function testExceptionGetGroundState()
    {
        $groundWithSnow = new GroundWithSnowDecoder('AAXX');
        $this->expectException(\Exception::class);

        $groundWithSnow->getGroundState();
    }

    public function testSuccessGetDepthSnow()
    {
        $this->assertEquals(['value' => 'Snow cover, not continuous'], $this->groundWithSnow->getDepthSnow());
    }

    public function testSuccessIsArrayGetDepthSnow()
    {
        $this->assertIsArray($this->groundWithSnow->getDepthSnow());
    }

    public function testErrorStringGetDepthSnow()
    {
        $this->assertNotEquals(['value' => 'Measurement impossible or inaccurate'], $this->groundWithSnow->getDepthSnow());
    }

    public function testSuccessIntegerGetDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('49025');
        $this->assertEquals(['value' => 25], $groundWithSnow->getDepthSnow());
    }

    public function testErrorIntegerGetDepthSnow()
    {
        $groundWithSnow = new GroundWithSnowDecoder('49020');
        $this->assertNotEquals(['value' => 25], $groundWithSnow->getDepthSnow());
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

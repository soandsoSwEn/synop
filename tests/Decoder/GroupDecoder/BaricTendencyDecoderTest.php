<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\BaricTendencyDecoder;
use Soandso\Synop\Fabrication\Validate;

class BaricTendencyDecoderTest extends TestCase
{
    private $baricTendencyDecoder;

    protected function setUp(): void
    {
        $this->baricTendencyDecoder = new BaricTendencyDecoder('52035');
    }

    protected function tearDown(): void
    {
        unset($this->baricTendencyDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals($result, '5');
    }

    public function testErrorGetCodeFigureIndicator()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIndicator');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals($result, 'A');
    }

    public function testSuccessGetCodeFigureCharacteristic()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureCharacteristic');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals($result, '2');
    }

    public function testErrorGetCodeFigureCharacteristic()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureCharacteristic');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals($result, '10');
    }

    public function testSuccessGetCodeFigureChange()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureChange');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertEquals($result, '035');
    }

    public function testErrorGetCodeFigureChange()
    {
        $reflector = new \ReflectionClass(BaricTendencyDecoder::class);
        $method = $reflector->getMethod('getCodeFigureChange');
        $method->setAccessible(true);
        $result = $method->invoke($this->baricTendencyDecoder);

        $this->assertNotEquals($result, '35');
    }

    public function testSuccessGetCharacteristicChange()
    {
        $this->assertEquals($this->baricTendencyDecoder->getCharacteristicChange(), 2);
    }

    public function testErrorGetCharacteristicChange()
    {
        $this->assertNotEquals($this->baricTendencyDecoder->getCharacteristicChange(), '');
    }

    public function testSuccessGetBaricTendency()
    {
        $this->assertEquals($this->baricTendencyDecoder->getBaricTendency(), 3.5);
    }

    public function testErrorGetBaricTendency()
    {
        $this->assertNotEquals($this->baricTendencyDecoder->getBaricTendency(), '035');
    }

    public function testSuccessIsGroup()
    {
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->baricTendencyDecoder->isGroup($validator));
    }

    public function testErrorIsGroup()
    {
        $baricTendencyDecoder = new BaricTendencyDecoder('52035');
        $validator = Mockery::mock(Validate::class);
        $validator->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertTrue($baricTendencyDecoder->isGroup($validator));
    }
}
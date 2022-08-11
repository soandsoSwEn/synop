<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\DateDecoder;
use Soandso\Synop\Fabrication\Validate;

class DateDecoderTest extends TestCase
{
    private $dateDecoder;

    protected function setUp(): void
    {
        $this->dateDecoder = new DateDecoder('07181');
    }

    protected function tearDown(): void
    {
        unset($this->dateDecoder);
        Mockery::close();
    }

    public function testSuccessGetIwElement()
    {
        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwElement');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertEquals('1', $result);
    }

    public function testSuccessIsStringGetIwElement()
    {
        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwElement');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetIwElement()
    {
        $dataDecoder = new DateDecoder('33837');
        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwElement');
        $method->setAccessible(true);
        $result = $method->invoke($dataDecoder);

        $this->assertNotEquals('1', $result);
    }

    public function testSuccessGetIwData()
    {
        $expected = [
            0 => ['Visual', 'm/s'],
            1 => ['Instrumental', 'm/s'],
            3 => ['Visual', 'knot'],
            4 => ['Instrumental', 'knot']
        ];

        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwData');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertEquals($expected, $result);
    }

    public function testSuccessIsArrayGetIwData()
    {
        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwData');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertIsArray($result);
    }

    public function testErrorGetIwData()
    {
        $expected = [
            0 => ['Visual', 'm/s'],
            1 => ['Instrumental', 'm/s'],
        ];

        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwData');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertNotEquals($expected, $result);
    }

    public function testSuccessGetIw()
    {
        $this->assertEquals(['Instrumental', 'm/s'], $this->dateDecoder->getIw());
    }

    public function testSuccessIsArrayGetIw()
    {
        $this->assertIsArray($this->dateDecoder->getIw());
    }

    public function testErrorGetIw()
    {
        $this->assertNotEquals(['Visual', 'm/s'], $this->dateDecoder->getIw());
    }

    public function testSuccessGetDay()
    {
        $this->assertEquals('07', $this->dateDecoder->getDay());
    }

    public function testSuccessIsStringGetDay()
    {
        $this->assertIsString($this->dateDecoder->getDay());
    }

    public function testErrorGetDay()
    {
        $this->assertNotEquals('81', $this->dateDecoder->getDay());
    }

    public function testSuccessIsStringGetHour()
    {
        $this->assertIsString($this->dateDecoder->getHour());
    }

    public function testErrorGetHour()
    {
        $this->assertNotEquals('25', $this->dateDecoder->getHour());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->dateDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $dataDecoder = new DateDecoder('33837');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($dataDecoder->isGroup($validate));
    }
}

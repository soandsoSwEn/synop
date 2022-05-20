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

        $this->assertEquals($result, '1');
    }

    public function testErrorGetIwElement()
    {
        $dataDecoder = new DateDecoder('33837');
        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwElement');
        $method->setAccessible(true);
        $result = $method->invoke($dataDecoder);

        $this->assertNotEquals($result, '1');
    }

    public function testSuccessGetIwData()
    {
        $actual = [
            0 => ['Visual', 'm/s'],
            1 => ['Instrumental', 'm/s'],
            3 => ['Visual', 'knot'],
            4 => ['Instrumental', 'knot']
        ];

        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwData');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertEquals($result, $actual);
    }

    public function testErrorGetIwData()
    {
        $actual = [
            0 => ['Visual', 'm/s'],
            1 => ['Instrumental', 'm/s'],
        ];

        $reflector = new \ReflectionClass(DateDecoder::class);
        $method = $reflector->getMethod('getIwData');
        $method->setAccessible(true);
        $result = $method->invoke($this->dateDecoder);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessGetIw()
    {
        $this->assertEquals($this->dateDecoder->getIw(), ['Instrumental', 'm/s']);
    }

    public function testErrorGetIw()
    {
        $this->assertNotEquals($this->dateDecoder->getIw(), ['Visual', 'm/s']);
    }

    public function testSuccessGetDay()
    {
        $this->assertEquals($this->dateDecoder->getDay(), '07');
    }

    public function testErrorGetDay()
    {
        $this->assertNotEquals($this->dateDecoder->getDay(), '81');
    }

    public function testSuccessGetHour()
    {
        $this->assertEquals($this->dateDecoder->getHour(), '18');
    }

    public function testErrorGetHour()
    {
        $this->assertNotEquals($this->dateDecoder->getHour(), '25');
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
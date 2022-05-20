<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\CloudWindDecoder;
use Soandso\Synop\Fabrication\Validate;

class CloudWindDecoderTest extends TestCase
{
    private $cloudWindDecoder;

    protected function setUp(): void
    {
        $this->cloudWindDecoder = new CloudWindDecoder('83102');
    }

    protected function tearDown(): void
    {
        unset($this->cloudWindDecoder);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureN()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureN');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals($result, '8');
    }

    public function testErrorGetCodeFigureN()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureN');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals($result, '5');
    }

    public function testSuccessGetCodeFigureDd()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDd');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals($result, '31');
    }

    public function testErrorGetCodeFigureDd()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDd');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals($result, '02');
    }

    public function testSuccessGetCodeFigureVv()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVv');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals($result, '02');
    }

    public function testErrorGetCodeFigureVv()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVv');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals($result, '31');
    }

    public function testSuccessGetN()
    {
        $this->assertEquals($this->cloudWindDecoder->getN(), '10');
    }

    public function testErrorGetN()
    {
        $this->assertNotEquals($this->cloudWindDecoder->getN(), '8');
    }

    public function testSuccessGetDd()
    {
        $this->assertEquals($this->cloudWindDecoder->getDd(), 310);
    }

    public function testErrorGetDd()
    {
        $this->assertNotEquals($this->cloudWindDecoder->getDd(), 31);
    }

    public function testSuccessGetVv()
    {
        $this->assertEquals($this->cloudWindDecoder->getVv(), 2);
    }

    public function testErrorGetVv()
    {
        $this->assertNotEquals($this->cloudWindDecoder->getVv(), 0.2);
    }

    public function testSuccessGetNData()
    {
        $actual = [
            '0' => '0',
            '1' => '1',
            '2' => '2-3',
            '3' => '4',
            '4' => '5',
            '5' => '6',
            '6' => '7-8',
            '7' => '9',
            '8' => '10',
            '9' => '-',
            '/' => ''
        ];

        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getNData');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals($result, $actual);
    }

    public function testSErrorGetNData()
    {
        $actual = [
            '0' => '0',
            '1' => '1',
            '2' => '2-3',
            '3' => '4',
            '4' => '5',
            '5' => '6',
            '6' => '7-8',
            '7' => '9',
            '8' => '10'
        ];

        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getNData');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->cloudWindDecoder->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $cloudWindDecoder = new CloudWindDecoder('11583');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($cloudWindDecoder->isGroup($validate));
    }
}
<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder;
use Soandso\Synop\Fabrication\Validate;

class LowCloudVisibilityDecoderTest extends TestCase
{
    private $lowCloudVisibility;

    protected function setUp(): void
    {
        $this->lowCloudVisibility = new LowCloudVisibilityDecoder('11583');
    }

    protected function tearDown(): void
    {
        unset($this->lowCloudVisibility);
        Mockery::close();
    }

    public function testSuccessGetCodeFigureIr()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIr');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, '1');
    }

    public function testErrorGetCodeFigureIr()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIr');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals($result, '1');
    }

    public function testSuccessGetCodeFigureIx()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIx');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, '1');
    }

    public function testErrorGetCodeFigureIx()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIx');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals($result, '1');
    }

    public function testSuccessGetCodeFigureH()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureH');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, '5');
    }

    public function testErrorGetCodeFigureH()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureH');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals($result, '5');
    }

    public function testSuccessGetCodeFigureVV()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVV');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, '83');
    }

    public function testErrorGetCodeFigureVV()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVV');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals($result, '83');
    }

    public function testSuccessGetHData()
    {
        $actual = [
            0 => '<50',
            1 => '500-100',
            2 => '100-200',
            3 => '200-300',
            4 => '300-600',
            5 => '600-1000',
            6 => '1000-1500',
            7 => '1500-2000',
            8 => '2000-2500',
            9 => 'no clouds below 2500'
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getHData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, $actual);
    }

    public function testErrorGetHData()
    {
        $actual = [
            0 => '<50',
            1 => '500-100',
            2 => '100-200',
            3 => '200-300',
            4 => '300-600',
            5 => '600-1000',
            6 => '1000-1500',
            7 => '1500-2000',
            8 => '2000-2500',
            9 => '/'
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getHData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessGetIxData()
    {
        $actual = [
            1 => ['Included', 'Manned'],
            2 => ['Omitted (no significant phenomenon to report)', 'Manned'],
            3 => ['Omitted (not observed, data not available)', 'Manned'],
            4 => ['Included', 'Automatic'],
            5 => ['Omitted (no significant phenomenon to report)', 'Automatic'],
            6 => ['Omitted (not observed, data not available)', 'Automatic']
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIxData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, $actual);
    }

    public function testErrorGetIxData()
    {
        $actual = [
            1 => ['Included', 'Manned'],
            2 => ['Omitted (no significant phenomenon to report)', 'Manned'],
            3 => ['Omitted (not observed, data not available)', 'Manned'],
            4 => ['Included', 'Automatic'],
            5 => ['Omitted (no significant phenomenon to report)', 'Automatic'],
            6 => []
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIxData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessGetIrData()
    {
        $actual = [
            1 => 'Included in section 1',
            2 => 'Included in section 3',
            3 => 'Omitted (precipitation amount = 0)',
            4 => 'Omitted (precipitation not amount available)'
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIrData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($result, $actual);
    }

    public function testErrorGetIrData()
    {
        $actual = [
            1 => ['Included', 'Manned'],
            2 => ['Omitted (no significant phenomenon to report)', 'Manned'],
            3 => ['Omitted (not observed, data not available)', 'Manned'],
            4 => ['Included', 'Automatic'],
            5 => ['Omitted (no significant phenomenon to report)', 'Automatic'],
            6 => ['Omitted (not observed, data not available)', 'Automatic']
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIrData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessGetVisValue()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getVisValue');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->lowCloudVisibility, ['83']);

        $this->assertEquals($result, 45);
    }

    public function testErrorGetVisValue()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getVisValue');
        $method->setAccessible(true);
        $result = $method->invokeArgs($lowCloudVisibility, ['02']);

        $this->assertNotEquals($result, 45);
    }

    public function testSuccessGetVV()
    {
        $this->assertEquals($this->lowCloudVisibility->getVV(), 45);
    }

    public function testErrorGetVV()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $this->assertNotEquals($lowCloudVisibility->getVV(), 45);
    }

    public function testSuccessGetH()
    {
        $this->assertEquals($this->lowCloudVisibility->getH(), '600-1000');
    }

    public function testErrorGetH()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $this->assertNotEquals($lowCloudVisibility->getH(), '600-1000');
    }

    public function testSuccessGetIx()
    {
        $this->assertEquals($this->lowCloudVisibility->getIx(), ['Included', 'Manned']);
    }

    public function testErrorGetIx()
    {
        $this->assertNotEquals($this->lowCloudVisibility->getIx(), ['Omitted (no significant phenomenon to report)', 'Manned']);
    }

    public function testSuccessGetIr()
    {
        $this->assertEquals($this->lowCloudVisibility->getIr(), 'Included in section 1');
    }

    public function testErrorGetIr()
    {
        $this->assertNotEquals($this->lowCloudVisibility->getIr(), 'Omitted (precipitation not amount available)');
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')
            ->withArgs(['Soandso\Synop\Decoder\GroupDecoder\LowCloudVisibilityDecoder', ['1', '1', '5', '83']])
            ->once()->andReturn(true);

        $this->assertTrue($this->lowCloudVisibility->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('AAXX');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($lowCloudVisibility->isGroup($validate));
    }
}

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

        $this->assertEquals('1', $result);
    }

    public function testSuccessIsStringGetCodeFigureIr()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIr');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIr()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIr');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals('1', $result);
    }

    public function testSuccessGetCodeFigureIx()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIx');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals('1', $result);
    }

    public function testSuccessIsStringGetCodeFigureIx()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIx');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureIx()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureIx');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals('1', $result);
    }

    public function testSuccessGetCodeFigureH()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureH');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals('5', $result);
    }

    public function testSuccessIsStringGetCodeFigureH()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureH');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureH()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureH');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals('5', $result);
    }

    public function testSuccessGetCodeFigureVV()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVV');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals('83', $result);
    }

    public function testSuccessIsStringGetCodeFigureVV()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVV');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureVV()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVV');
        $method->setAccessible(true);
        $result = $method->invoke($lowCloudVisibility);

        $this->assertNotEquals('83', $result);
    }

    public function testSuccessGetHData()
    {
        $expected = [
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

        $this->assertEquals($expected, $result);
    }

    public function testSuccessIsArrayGetHData()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getHData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsArray($result);
    }

    public function testErrorGetHData()
    {
        $expected = [
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

        $this->assertNotEquals($expected, $result);
    }

    public function testSuccessGetIxData()
    {
        $expected = [
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

        $this->assertEquals($expected, $result);
    }

    public function testSuccessIsAraayGetIxData()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIxData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsArray($result);
    }

    public function testErrorGetIxData()
    {
        $expected = [
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

        $this->assertNotEquals($expected, $result);
    }

    public function testSuccessGetIrData()
    {
        $expected = [
            1 => 'Included in section 1',
            2 => 'Included in section 3',
            3 => 'Omitted (precipitation amount = 0)',
            4 => 'Omitted (precipitation not amount available)'
        ];

        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIrData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertEquals($expected, $result);
    }

    public function testSuccessIsArrayGetIrData()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getIrData');
        $method->setAccessible(true);
        $result = $method->invoke($this->lowCloudVisibility);

        $this->assertIsArray($result);
    }

    public function testErrorGetIrData()
    {
        $expected = [
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

        $this->assertNotEquals($expected, $result);
    }

    public function testSuccessGetVisValue()
    {
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getVisValue');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->lowCloudVisibility, ['83']);

        $this->assertEquals(45, $result);
    }

    public function testErrorGetVisValue()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $reflector = new \ReflectionClass(LowCloudVisibilityDecoder::class);
        $method = $reflector->getMethod('getVisValue');
        $method->setAccessible(true);
        $result = $method->invokeArgs($lowCloudVisibility, ['02']);

        $this->assertNotEquals(45, $result);
    }

    public function testSuccessGetVV()
    {
        $this->assertEquals(45, $this->lowCloudVisibility->getVV());
    }

    public function testErrorGetVV()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $this->assertNotEquals(45, $lowCloudVisibility->getVV());
    }

    public function testSuccessGetH()
    {
        $this->assertEquals('600-1000', $this->lowCloudVisibility->getH());
    }

    public function testErrorGetH()
    {
        $lowCloudVisibility = new LowCloudVisibilityDecoder('83102');
        $this->assertNotEquals('600-1000', $lowCloudVisibility->getH());
    }

    public function testSuccessGetIx()
    {
        $this->assertEquals(['Included', 'Manned'], $this->lowCloudVisibility->getIx());
    }

    public function testSuccessIsArrayGetIx()
    {
        $this->assertIsArray($this->lowCloudVisibility->getIx());
    }

    public function testErrorGetIx()
    {
        $this->assertNotEquals(
            ['Omitted (no significant phenomenon to report)', 'Manned'],
            $this->lowCloudVisibility->getIx()
        );
    }

    public function testSuccessGetIr()
    {
        $this->assertEquals('Included in section 1', $this->lowCloudVisibility->getIr());
    }

    public function testSuccessIsStringGetIr()
    {
        $this->assertIsString($this->lowCloudVisibility->getIr());
    }

    public function testNullGetIr()
    {
        $reflectorProperty = new \ReflectionProperty(LowCloudVisibilityDecoder::class, 'raw_cloud_vis');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->lowCloudVisibility, '5');

        $this->assertNull($this->lowCloudVisibility->getIr());
    }

    public function testErrorGetIr()
    {
        $this->assertNotEquals('Omitted (precipitation not amount available)', $this->lowCloudVisibility->getIr());
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

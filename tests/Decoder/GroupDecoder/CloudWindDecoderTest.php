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

        $this->assertEquals('8', $result);
    }

    public function testSuccessIsStringGetCodeFigureN()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureN');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureN()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureN');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals('5', $result);
    }

    public function testSuccessGetCodeFigureDd()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDd');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals('31', $result);
    }

    public function testSuccessIsStringGetCodeFigureDd()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDd');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureDd()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureDd');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals('02', $result);
    }

    public function testSuccessGetCodeFigureVv()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVv');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertEquals('02', $result);
    }

    public function testSuccessIsStringGetCodeFigureVv()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVv');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertIsString($result);
    }

    public function testErrorGetCodeFigureVv()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getCodeFigureVv');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertNotEquals('31', $result);
    }

    public function testSuccessGetN()
    {
        $this->assertEquals('10', $this->cloudWindDecoder->getN());
    }

    public function testSuccessIsStringGetN()
    {
        $this->assertIsString($this->cloudWindDecoder->getN());
    }

    public function testNullGetN()
    {
        $reflectorProperty = new \ReflectionProperty(CloudWindDecoder::class, 'rawCloudsWind');
        $reflectorProperty->setAccessible(true);
        $reflectorProperty->setValue($this->cloudWindDecoder, 'A3102');

        $this->assertNull($this->cloudWindDecoder->getN());
    }

    public function testErrorGetN()
    {
        $this->assertNotEquals('8', $this->cloudWindDecoder->getN());
    }

    public function testSuccessGetDd()
    {
        $this->assertEquals(310, $this->cloudWindDecoder->getDd());
    }

    public function testSuccessIsIntGetDd()
    {
        $this->assertIsInt($this->cloudWindDecoder->getDd());
    }

    public function testErrorGetDd()
    {
        $this->assertNotEquals(31, $this->cloudWindDecoder->getDd());
    }

    public function testSuccessGetVv()
    {
        $this->assertEquals(2, $this->cloudWindDecoder->getVv());
    }

    public function testSuccessIsIntGetVv()
    {
        $this->assertIsInt($this->cloudWindDecoder->getVv());
    }

    public function testErrorGetVv()
    {
        $this->assertNotEquals(0.2, $this->cloudWindDecoder->getVv());
    }

    public function testSuccessGetNData()
    {
        $expected = [
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

        $this->assertEquals($expected, $result);
    }

    public function testSuccessIsArrayGetNData()
    {
        $reflector = new \ReflectionClass(CloudWindDecoder::class);
        $method = $reflector->getMethod('getNData');
        $method->setAccessible(true);
        $result = $method->invoke($this->cloudWindDecoder);

        $this->assertIsArray($result);
    }

    public function testSErrorGetNData()
    {
        $expected = [
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

        $this->assertNotEquals($expected, $result);
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

    public function testSuccessGetTotalCloudIndicator()
    {
        $expected = ['N' => 'Total amount of cloud'];

        $this->assertEquals($expected, $this->cloudWindDecoder->getTotalCloudIndicator());
    }

    public function testSuccessIsArrayGetTotalCloudIndicator()
    {
        $this->assertIsArray($this->cloudWindDecoder->getTotalCloudIndicator());
    }

    public function testSuccessGetWindDirectionIndicator()
    {
        $expected = ['dd' => 'Wind direction in tens degrees'];

        $this->assertEquals($expected, $this->cloudWindDecoder->getWindDirectionIndicator());
    }

    public function testSuccessIsArrayGetWindDirectionIndicator()
    {
        $this->assertIsArray($this->cloudWindDecoder->getWindDirectionIndicator());
    }

    public function testSuccessGetWindSpeedIndicator()
    {
        $expected = ['ff' => 'Wind speed'];

        $this->assertEquals($expected, $this->cloudWindDecoder->getWindSpeedIndicator());
    }
}

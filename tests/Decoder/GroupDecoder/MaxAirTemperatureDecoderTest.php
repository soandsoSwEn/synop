<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\MaxAirTemperatureDecoder;
use Soandso\Synop\Fabrication\Validate;

class MaxAirTemperatureDecoderTest extends TestCase
{
    private $maxAirTemperatureDecoder;

    public function setUp(): void
    {
        $this->maxAirTemperatureDecoder = new MaxAirTemperatureDecoder('11011');
    }

    public function tearDown(): void
    {
        unset($this->maxAirTemperatureDecoder);
        Mockery::close();
    }

    public function testSuccessGetIndicatorGroup()
    {
        $this->assertEquals(['1' => 'Indicator'], $this->maxAirTemperatureDecoder->getIndicatorGroup());
    }

    public function testSuccessIsArrayGetIndicatorGroup()
    {
        $this->assertIsArray($this->maxAirTemperatureDecoder->getIndicatorGroup());
    }

    public function testSuccessGetSignTemperatureIndicator()
    {
        $this->assertEquals(['Sn' => 'Sign of temperature'], $this->maxAirTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessIsArrayGetSignTemperatureIndicator()
    {
        $this->assertIsArray($this->maxAirTemperatureDecoder->getSignTemperatureIndicator());
    }

    public function testSuccessGetDryBulbTemperatureIndicator()
    {
        $this->assertEquals(
            ['TxTxTx' => 'Maximum temperature in tenths of a degree'],
            $this->maxAirTemperatureDecoder->getDryBulbTemperatureIndicator()
        );
    }

    public function testSuccessIsArrayGetDryBulbTemperatureIndicator()
    {
        $this->assertIsArray($this->maxAirTemperatureDecoder->getDryBulbTemperatureIndicator());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->assertTrue($this->maxAirTemperatureDecoder->isGroup($validate, '2SnTnTnTn'));
    }

    public function testErrorIsGroup()
    {
        $minAirTemperatureGroup = new maxAirTemperatureDecoder('21065');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(false);

        $this->assertFalse($minAirTemperatureGroup->isGroup($validate, '1SnTxTxTx'));
    }
}

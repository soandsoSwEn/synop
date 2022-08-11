<?php

namespace Soandso\Synop\Tests\Decoder\GroupDecoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\IndexDecoder;
use Soandso\Synop\Fabrication\Validate;

class IndexDecoderTest extends TestCase
{
    private $rawIndex;

    protected function setUp(): void
    {
        $this->rawIndex = new IndexDecoder('33837');
    }

    protected function tearDown(): void
    {
        unset($this->rawIndex);
        Mockery::close();
    }

    public function testSuccessGetArea()
    {
        $this->assertEquals('33', $this->rawIndex->getArea());
    }

    public function testSuccessIsStringGetArea()
    {
        $this->assertIsString($this->rawIndex->getArea());
    }

    public function testErrorGetArea()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals('33', $rawIndex->getArea());
    }

    public function testSuccessGetNumber()
    {
        $this->assertEquals('837', $this->rawIndex->getNumber());
    }

    public function testSuccessIsStringGetNumber()
    {
        $this->assertIsString($this->rawIndex->getNumber());
    }

    public function testErrorGetNumber()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals('837', $rawIndex->getNumber());
    }

    public function testSuccessGetIndex()
    {
        $this->assertEquals('33837', $this->rawIndex->getIndex());
    }

    public function testSuccessIsStringGetIndex()
    {
        $this->assertIsString($this->rawIndex->getIndex());
    }

    public function testErrorGetIndex()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals('33837', $rawIndex->getIndex());
    }

    public function testSuccessIsGroup()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->withArgs(['Soandso\Synop\Decoder\GroupDecoder\IndexDecoder', ['33', '837']])->once()->andReturn(true);

        $this->assertTrue($this->rawIndex->isGroup($validate));
    }

    public function testErrorIsGroup()
    {
        $rawIndex = new IndexDecoder('11583');
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->withArgs(['Soandso\Synop\Decoder\GroupDecoder\IndexDecoder', ['11', '583']])->once()->andReturn(false);

        $this->assertFalse($rawIndex->isGroup($validate));
    }
}

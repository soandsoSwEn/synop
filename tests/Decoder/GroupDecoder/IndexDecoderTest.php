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
        $this->assertEquals($this->rawIndex->getArea(), '33');
    }

    public function testErrorGetArea()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals($rawIndex->getArea(), '33');
    }

    public function testSuccessGetNumber()
    {
        $this->assertEquals($this->rawIndex->getNumber(), '837');
    }

    public function testErrorGetNumber()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals($rawIndex->getNumber(), '837');
    }

    public function testSuccessGetIndex()
    {
        $this->assertEquals($this->rawIndex->getIndex(), '33837');
    }

    public function testErrorGetIndex()
    {
        $rawIndex = new IndexDecoder('11583');
        $this->assertNotEquals($rawIndex->getIndex(), '33837');
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
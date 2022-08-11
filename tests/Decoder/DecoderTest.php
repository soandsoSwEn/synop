<?php

namespace Soandso\Synop\Tests\Decoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\Decoder;
use Soandso\Synop\Fabrication\RawReport;

class DecoderTest extends TestCase
{
    private $decoder;
    private $report = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004=';

    protected function setUp(): void
    {
        $this->decoder = new Decoder();
    }

    protected function tearDown(): void
    {
        unset($this->decoder);
    }

    public function testSuccessBlock()
    {
        $expected = 'AAXX';
        $this->assertEquals($expected, $this->decoder->block($this->report));
    }

    public function testSuccessIsStringBlock()
    {
        $this->assertIsString($this->decoder->block($this->report));
    }

    public function testErrorBlock()
    {
        $this->assertNotEquals('', $this->decoder->block($this->report));
    }

    public function testSuccessEndBlock()
    {
        $expected = 'AAXX 07181 33837 11583 83102 10039 21007 30049 40101 52035 60012 70282 8255/ 333 10091 555 1/004';
        $this->assertEquals($expected, $this->decoder->endBlock($this->report));
    }

    public function testSuccessIsStringEndBlock()
    {
        $this->assertIsString($this->decoder->endBlock($this->report));
    }

    public function testErrorEndBlock()
    {
        $expected = '1/004';
        $this->assertNotEquals($expected, $this->decoder->endBlock($this->report));
    }

    public function testSuccessUpdateReport(): void
    {
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($this->report);
        $rawReport->shouldReceive('updateReport')->once();
        $this->decoder->updateReport('AAXX', $rawReport);

        //$this->assertEmpty($this->decoder->updateReport('AAXX', $rawReport));
        $this->assertInstanceOf(Decoder::class, $this->decoder);
    }
}
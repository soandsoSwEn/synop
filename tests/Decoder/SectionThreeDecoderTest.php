<?php

namespace Soandso\Synop\Tests\Decoder;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\SectionThreeDecoder;
use Soandso\Synop\Fabrication\RawReport;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\Section;
use Soandso\Synop\Sheme\SectionInterface;

class SectionThreeDecoderTest extends TestCase
{
    private $sectionThreeDecoder;

    protected function setUp(): void
    {
        $this->sectionThreeDecoder = new SectionThreeDecoder(new Section('Section Three'), true, false, new Unit());
    }

    protected function tearDown(): void
    {
        unset($this->sectionThreeDecoder);
        Mockery::close();
    }

    public function testSuccessIsGroup()
    {
        $this->assertTrue($this->sectionThreeDecoder->isGroup('10091', 5));
    }

    public function testErrorIsGroup()
    {
        $this->assertFalse($this->sectionThreeDecoder->isGroup('AAXX', 5));
    }

    public function testSuccessGet1SnTxTxTx()
    {
        $report = '10091 21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '21021 32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get1SnTxTxTx($rawReport, $validate));
    }

    public function testNullGet1SnTxTxTx()
    {
        $report = '1009 21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '21021 32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get1SnTxTxTx($rawReport, $validate));
    }

    public function testErrorGet1SnTxTxTx()
    {
        $report = '00091 21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '21021 32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get1SnTxTxTx($rawReport, $validate));
    }

    public function testSuccessGet2SnTnTnTn()
    {
        $report = '21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get2SnTnTnTn($rawReport, $validate));
    }

    public function testNullGet2SnTnTnTn()
    {
        $report = '2102 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get2SnTnTnTn($rawReport, $validate));
    }

    public function testErrorGet2SnTnTnTn()
    {
        $report = '01021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '32103 45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get2SnTnTnTn($rawReport, $validate));
    }

    public function testSuccessGet3ESnTgTg()
    {
        $report = '32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get3ESnTgTg($rawReport, $validate));
    }

    public function testNullGet3ESnTgTg()
    {
        $report = '3210 45997 55080 444 92952 555 1/004';
        $updatedReport = '45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get3ESnTgTg($rawReport, $validate));
    }

    public function testErrorGet3ESnTgTg()
    {
        $report = '21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '45997 55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get3ESnTgTg($rawReport, $validate));
    }

    public function testSuccessGet4Esss()
    {
        $report = '45997 55080 444 92952 555 1/004';
        $updatedReport = '55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get4Esss($rawReport, $validate));
    }

    public function testNullGet4Esss()
    {
        $report = '4599 55080 444 92952 555 1/004';
        $updatedReport = '55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get4Esss($rawReport, $validate));
    }

    public function testErroGet4Esss()
    {
        $report = '05997 55080 444 92952 555 1/004';
        $updatedReport = '55080 444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get4Esss($rawReport, $validate));
    }

    public function testSuccessGet55SSS()
    {
        $report = '55080 444 92952 555 1/004';
        $updatedReport = '444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get55SSS($rawReport, $validate));
    }

    public function testNullGet55SSS()
    {
        $report = '5508 444 92952 555 1/004';
        $updatedReport = '444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get55SSS($rawReport, $validate));
    }

    public function testErrorGet55SSS()
    {
        $report = '21021 32103 45997 55080 444 92952 555 1/004';
        $updatedReport = '444 92952 555 1/004';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get55SSS($rawReport, $validate));
    }

    public function testSuccessGet6RRRtr()
    {
        $report = '60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testNullGet6RRRtr()
    {
        $report = '6001 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testErrorGet6RRRtr()
    {
        $report = '55080 60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get6RRRtr($rawReport, $validate));
    }

    public function testSuccessGet8NsChshs()
    {
        $report = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get8NsChshs($rawReport, $validate));
    }

    public function testNullGet8NsChshs()
    {
        $report = '88// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get8NsChshs($rawReport, $validate));
    }

    public function testErrorGet8NsChshs()
    {
        $report = '60011 885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get8NsChshs($rawReport, $validate));
    }

    public function testSuccessGet9SpSpspsp()
    {
        $report = '91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->twice()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->once()->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertTrue($this->sectionThreeDecoder->get9SpSpspsp($rawReport, $validate));
    }

    public function testNullGet9SpSpspsp()
    {
        $report = '9112 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get9SpSpspsp($rawReport, $validate));
    }

    public function testErrorGet9SpSpspsp()
    {
        $report = '885// 91124 444 92952 555 10178 3/021 41022 60021 77182 92952';
        $updatedReport = '444 92952 555 10178 3/021 41022 60021 77182 92952';
        $rawReport = Mockery::mock(RawReport::class);
        $rawReport->shouldReceive('getReport')->once()->andReturn($report);
        $rawReport->shouldReceive('updateReport')->andReturn($updatedReport);

        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->assertNull($this->sectionThreeDecoder->get9SpSpspsp($rawReport, $validate));
    }

    public function testSuccessPutInSection()
    {
        $data = '444 92952 555 10178 3/021 41022 60021 77182 92952';
        $reflector = new \ReflectionClass(SectionThreeDecoder::class);
        $method = $reflector->getMethod('putInSection');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sectionThreeDecoder, [$data]);

        $this->assertTrue($result);
    }

    public function testSuccessParse()
    {
        $reflector = new \ReflectionClass(SectionThreeDecoder::class);
        $method = $reflector->getMethod('parse');
        $method->setAccessible(true);
        $result = $method->invoke($this->sectionThreeDecoder);

        $this->assertInstanceOf(SectionInterface::class, $result);
    }
}

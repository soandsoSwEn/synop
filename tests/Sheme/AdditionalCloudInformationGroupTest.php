<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\AdditionalCloudInformationDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Unit;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\AdditionalCloudInformationGroup;

class AdditionalCloudInformationGroupTest extends TestCase
{
    private $additionalCloudInformationGroup;

    protected function setUp(): void
    {
        $unit = Mockery::mock(Unit::class);
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->additionalCloudInformationGroup = new AdditionalCloudInformationGroup('88518', $unit, $validate);
    }

    protected function tearDown(): void
    {
        unset($this->additionalCloudInformationGroup);
        Mockery::close();
    }

    public function testSuccessSetHeightCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getHeightCloud')->once()->andReturn(['Height' => 540]);

        $this->additionalCloudInformationGroup->setHeightCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals(['Height' => 540], $value);
    }

    public function testSuccessIsArraySetHeightCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getHeightCloud')->once()->andReturn(['Height' => 540]);

        $this->additionalCloudInformationGroup->setHeightCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsArray($value);
    }

    public function testNullSetHeightCloud()
    {
        $this->additionalCloudInformationGroup->setHeightCloud(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetCodeHeightCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getCodeHeightCloud')->once()->andReturn('18');

        $this->additionalCloudInformationGroup->setCodeHeightCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('18', $value);
    }

    public function testSuccessIsStringSetCodeHeightCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getCodeHeightCloud')->once()->andReturn('18');

        $this->additionalCloudInformationGroup->setCodeHeightCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetCodeHeightCloud()
    {
        $this->additionalCloudInformationGroup->setCodeHeightCloud(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getFormCloud')->once()->andReturn('Nimbostratus (Ns)');

        $this->additionalCloudInformationGroup->setFormCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('Nimbostratus (Ns)', $value);
    }

    public function testSuccessIsStringSetFormCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getFormCloud')->once()->andReturn('Nimbostratus (Ns)');

        $this->additionalCloudInformationGroup->setFormCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormCloud()
    {
        $this->additionalCloudInformationGroup->setFormCloud(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetAmountCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getAmountCloud')->once()->andReturn('Sky completely covered');

        $this->additionalCloudInformationGroup->setAmountCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('Sky completely covered', $value);
    }

    public function testSuccessIsStringSetAmountCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getAmountCloud')->once()->andReturn('Sky completely covered');

        $this->additionalCloudInformationGroup->setAmountCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetAmountCloud()
    {
        $this->additionalCloudInformationGroup->setAmountCloud(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetCodeAmountCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getCodeAmountCloud')->once()->andReturn('8');

        $this->additionalCloudInformationGroup->setCodeAmountCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('8', $value);
    }

    public function testSuccessIsStringSetCodeAmountCloud()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('getCodeAmountCloud')->once()->andReturn('8');

        $this->additionalCloudInformationGroup->setCodeAmountCloud($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetCodeAmountCloud()
    {
        $this->additionalCloudInformationGroup->setCodeAmountCloud(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsAddCloudGroup()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->additionalCloudInformationGroup->isAddCloudGroup($decoder, $validate));
    }

    public function testSuccessSetAdditionalCloudInformationGroup()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getCodeAmountCloud')->once()->andReturn('8');
        $decoder->shouldReceive('getAmountCloud')->once()->andReturn('Sky completely covered');
        $decoder->shouldReceive('getFormCloud')->once()->andReturn('Nimbostratus (Ns)');
        $decoder->shouldReceive('getCodeHeightCloud')->once()->andReturn('18');
        $decoder->shouldReceive('getHeightCloud')->once()->andReturn(['Height' => 540]);

        $validate = Mockery::mock(Validate::class);

        $this->additionalCloudInformationGroup->setAdditionalCloudInformationGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $propertyCode = $reflector->getProperty('codeAmountCloud');
        $propertyCode->setAccessible(true);
        $valueCode = $propertyCode->getValue($this->additionalCloudInformationGroup);

        $propertyAmount = $reflector->getProperty('amountCloud');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->additionalCloudInformationGroup);

        $propertyForm = $reflector->getProperty('formCloud');
        $propertyForm->setAccessible(true);
        $valueForm = $propertyForm->getValue($this->additionalCloudInformationGroup);

        $propertyCodeHeight = $reflector->getProperty('codeHeightCloud');
        $propertyCodeHeight->setAccessible(true);
        $valueCodeHeight = $propertyCodeHeight->getValue($this->additionalCloudInformationGroup);

        $propertyHeight = $reflector->getProperty('heightCloud');
        $propertyHeight->setAccessible(true);
        $valueHeight = $propertyHeight->getValue($this->additionalCloudInformationGroup);

        $expected = [
            '8', 'Sky completely covered', 'Nimbostratus (Ns)', '18', ['Height' => 540]
        ];

        $this->assertEquals($expected, [$valueCode, $valueAmount, $valueForm, $valueCodeHeight, $valueHeight]);
    }

    public function testNullSetAdditionalCloudInformationGroup()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->additionalCloudInformationGroup->setAdditionalCloudInformationGroup($decoder, $validate);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $propertyCode = $reflector->getProperty('codeAmountCloud');
        $propertyCode->setAccessible(true);
        $valueCode = $propertyCode->getValue($this->additionalCloudInformationGroup);

        $propertyAmount = $reflector->getProperty('amountCloud');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->additionalCloudInformationGroup);

        $propertyForm = $reflector->getProperty('formCloud');
        $propertyForm->setAccessible(true);
        $valueForm = $propertyForm->getValue($this->additionalCloudInformationGroup);

        $propertyCodeHeight = $reflector->getProperty('codeHeightCloud');
        $propertyCodeHeight->setAccessible(true);
        $valueCodeHeight = $propertyCodeHeight->getValue($this->additionalCloudInformationGroup);

        $propertyHeight = $reflector->getProperty('heightCloud');
        $propertyHeight->setAccessible(true);
        $valueHeight = $propertyHeight->getValue($this->additionalCloudInformationGroup);

        $expected = [null, null, null, null, null];

        $this->assertEquals($expected, [$valueCode, $valueAmount, $valueForm, $valueCodeHeight, $valueHeight]);
    }

    public function testSuccessGetHeightCloudValue()
    {
        $this->assertEquals(['Height' => 540], $this->additionalCloudInformationGroup->getHeightCloudValue());
    }

    public function testSuccessIsArrayGetHeightCloudValue()
    {
        $this->assertIsArray($this->additionalCloudInformationGroup->getHeightCloudValue());
    }

    public function testSuccessGetCodeHeightCloudValue()
    {
        $this->assertEquals('18', $this->additionalCloudInformationGroup->getCodeHeightCloudValue());
    }

    public function testSuccessIsStringGetCodeHeightCloudValue()
    {
        $this->assertIsString($this->additionalCloudInformationGroup->getCodeHeightCloudValue());
    }

    public function testSuccessGetFormCloudValue()
    {
        $this->assertEquals('Nimbostratus (Ns)', $this->additionalCloudInformationGroup->getFormCloudValue());
    }

    public function testSuccessIsStringGetFormCloudValue()
    {
        $this->assertIsString($this->additionalCloudInformationGroup->getFormCloudValue());
    }

    public function testSuccessGetAmountCloudValue()
    {
        $this->assertEquals('Sky completely covered', $this->additionalCloudInformationGroup->getAmountCloudValue());
    }

    public function testSuccessIsStringGetAmountCloudValue()
    {
        $this->assertIsString($this->additionalCloudInformationGroup->getAmountCloudValue());
    }

    public function testSuccessGetCodeAmountCloudValue()
    {
        $this->assertEquals('8', $this->additionalCloudInformationGroup->getCodeAmountCloudValue());
    }

    public function testSuccessIsStringGetCodeAmountCloudValue()
    {
        $this->assertIsString($this->additionalCloudInformationGroup->getCodeAmountCloudValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->additionalCloudInformationGroup->getDecoder());
    }

    public function testSuccessGetRawAdditionCloudInformation()
    {
        $this->assertEquals('88518', $this->additionalCloudInformationGroup->getRawAdditionCloudInformation());
    }

    public function testSuccessIsStringGetRawAdditionCloudInformation()
    {
        $this->assertIsString($this->additionalCloudInformationGroup->getRawAdditionCloudInformation());
    }

    public function testSuccessSetHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setHeightCloudValue(['Height' => 540]);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals(['Height' => 540], $value);
    }

    public function testSuccessIsArraySetHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setHeightCloudValue(['Height' => 540]);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsArray($value);
    }

    public function testNullSetHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setHeightCloudValue(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('heightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetCodeHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeHeightCloudValue('18');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('18', $value);
    }

    public function testSuccessIsStringSetCodeHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeHeightCloudValue('18');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetCodeHeightCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeHeightCloudValue(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeHeightCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormCloudValue()
    {
        $this->additionalCloudInformationGroup->setFormCloudValue('Nimbostratus (Ns)');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('Nimbostratus (Ns)', $value);
    }

    public function testSuccessIsStringSetFormCloudValue()
    {
        $this->additionalCloudInformationGroup->setFormCloudValue('Nimbostratus (Ns)');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormCloudValue()
    {
        $this->additionalCloudInformationGroup->setFormCloudValue(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('formCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setAmountCloudValue('Sky completely covered');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('Sky completely covered', $value);
    }

    public function testSuccessIsStringSetAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setAmountCloudValue('Sky completely covered');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setAmountCloudValue(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('amountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetCodeAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeAmountCloudValue('8');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('8', $value);
    }

    public function testSuccessIsStringSetCodeAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeAmountCloudValue('8');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testNullSetCodeAmountCloudValue()
    {
        $this->additionalCloudInformationGroup->setCodeAmountCloudValue(null);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('codeAmountCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(AdditionalCloudInformationDecoder::class);
        $this->additionalCloudInformationGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetRawAdditionCloudInformation()
    {
        $this->additionalCloudInformationGroup->setRawAdditionCloudInformation('88415');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('rawAdditionCloudInformation');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertEquals('88415', $value);
    }

    public function testSuccessIsStringSetRawAdditionCloudInformation()
    {
        $this->additionalCloudInformationGroup->setRawAdditionCloudInformation('88415');

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $property = $reflector->getProperty('rawAdditionCloudInformation');
        $property->setAccessible(true);
        $value = $property->getValue($this->additionalCloudInformationGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->additionalCloudInformationGroup->setData('88518', $validate);

        $reflector = new \ReflectionClass(AdditionalCloudInformationGroup::class);
        $propertyRawData = $reflector->getProperty('rawAdditionCloudInformation');
        $propertyRawData->setAccessible(true);
        $valueRawData = $propertyRawData->getValue($this->additionalCloudInformationGroup);

        $propertyDecoder = $reflector->getProperty('decoder');
        $propertyDecoder->setAccessible(true);
        $valueDecoder = $propertyDecoder->getValue($this->additionalCloudInformationGroup);

        $propertyCode = $reflector->getProperty('codeAmountCloud');
        $propertyCode->setAccessible(true);
        $valueCode = $propertyCode->getValue($this->additionalCloudInformationGroup);

        $propertyAmount = $reflector->getProperty('amountCloud');
        $propertyAmount->setAccessible(true);
        $valueAmount = $propertyAmount->getValue($this->additionalCloudInformationGroup);

        $propertyForm = $reflector->getProperty('formCloud');
        $propertyForm->setAccessible(true);
        $valueForm = $propertyForm->getValue($this->additionalCloudInformationGroup);

        $propertyCodeHeight = $reflector->getProperty('codeHeightCloud');
        $propertyCodeHeight->setAccessible(true);
        $valueCodeHeight = $propertyCodeHeight->getValue($this->additionalCloudInformationGroup);

        $propertyHeight = $reflector->getProperty('heightCloud');
        $propertyHeight->setAccessible(true);
        $valueHeight = $propertyHeight->getValue($this->additionalCloudInformationGroup);

        $expected = [
            '88518', true, '8', 'Sky completely covered', 'Nimbostratus (Ns)', '18', ['Height' => 540]
        ];

        $this->assertEquals($expected, [$valueRawData, $valueDecoder instanceof GroupDecoderInterface, $valueCode, $valueAmount, $valueForm, $valueCodeHeight, $valueHeight]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->additionalCloudInformationGroup->setData('', $validate);
    }
}

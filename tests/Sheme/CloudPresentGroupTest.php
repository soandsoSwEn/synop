<?php

namespace Soandso\Synop\Tests\Sheme;

use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\Synop\Decoder\GroupDecoder\CloudPresentDecoder;
use Soandso\Synop\Decoder\GroupDecoder\GroupDecoderInterface;
use Soandso\Synop\Fabrication\Validate;
use Soandso\Synop\Sheme\CloudPresentGroup;

class CloudPresentGroupTest extends TestCase
{
    private $cloudPresentGroup;

    protected function setUp(): void
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->andReturn(true);

        $this->cloudPresentGroup = new CloudPresentGroup('8255/', $validate);
    }

    protected function tearDown(): void
    {
        unset($this->cloudPresentGroup);
        Mockery::close();
    }

    public function testSuccessSetFormHighCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormHighCloud')->once()->andReturn('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant');

        $this->cloudPresentGroup->setFormHighCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant', $value);
    }

    public function testSuccessIsStringSetFormHighCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormHighCloud')->once()->andReturn('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant');

        $this->cloudPresentGroup->setFormHighCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormHighCloud()
    {
        $this->cloudPresentGroup->setFormHighCloud(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormHighCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormHighCloudSymbol')->once()->andReturn('9');

        $this->cloudPresentGroup->setFormHighCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('9', $value);
    }

    public function testSuccessIsStringSetFormHighCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormHighCloudSymbol')->once()->andReturn('9');

        $this->cloudPresentGroup->setFormHighCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormHighCloudSymbol()
    {
        $this->cloudPresentGroup->setFormHighCloudSymbol(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormMediumCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormMediumCloud')->once()->andReturn('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance');

        $this->cloudPresentGroup->setFormMediumCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance', $value);
    }

    public function testSuccessIsStringSetFormMediumCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormMediumCloud')->once()->andReturn('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance');

        $this->cloudPresentGroup->setFormMediumCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormMediumCloud()
    {
        $this->cloudPresentGroup->setFormMediumCloud(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormMediumCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormMediumCloudSymbol')->once()->andReturn('4');

        $this->cloudPresentGroup->setFormMediumCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('4', $value);
    }

    public function testSuccessIsStringSetFormMediumCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormMediumCloudSymbol')->once()->andReturn('4');

        $this->cloudPresentGroup->setFormMediumCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullStringSetFormMediumCloudSymbol()
    {
        $this->cloudPresentGroup->setFormMediumCloudSymbol(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormLowCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormLowCloud')->once()->andReturn('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present');

        $this->cloudPresentGroup->setFormLowCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present', $value);
    }

    public function testSuccessIsStringSetFormLowCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormLowCloud')->once()->andReturn('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present');

        $this->cloudPresentGroup->setFormLowCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormLowCloud()
    {
        $this->cloudPresentGroup->setFormLowCloud(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetFormLowCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormLowCloudSymbol')->once()->andReturn('4');

        $this->cloudPresentGroup->setFormLowCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('4', $value);
    }

    public function testSuccessIsStringSetFormLowCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getFormLowCloudSymbol')->once()->andReturn('4');

        $this->cloudPresentGroup->setFormLowCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetFormLowCloudSymbol()
    {
        $this->cloudPresentGroup->setFormLowCloudSymbol(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetAmountLowCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getAmountLowCloud')->once()->andReturn('1 eight of sky covered, or less, but not zero');

        $this->cloudPresentGroup->setAmountLowCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('1 eight of sky covered, or less, but not zero', $value);
    }

    public function testSuccessIsStringSetAmountLowCloud()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getAmountLowCloud')->once()->andReturn('1 eight of sky covered, or less, but not zero');

        $this->cloudPresentGroup->setAmountLowCloud($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetAmountLowCloud()
    {
        $this->cloudPresentGroup->setAmountLowCloud(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessSetAmountLowCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getAmountLowCloudSymbol')->once()->andReturn('1');

        $this->cloudPresentGroup->setAmountLowCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('1', $value);
    }

    public function testSuccessIsStringSetAmountLowCloudSymbol()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('getAmountLowCloudSymbol')->once()->andReturn('1');

        $this->cloudPresentGroup->setAmountLowCloudSymbol($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testNullSetAmountLowCloudSymbol()
    {
        $this->cloudPresentGroup->setAmountLowCloudSymbol(null);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertNull($value);
    }

    public function testSuccessIsCloudPresentGroup()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $validate = Mockery::mock(Validate::class);

        $this->assertTrue($this->cloudPresentGroup->isCloudPresentGroup($decoder, $validate));
    }

    public function testErrorIsCloudPresentGroup()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);
        $validate = Mockery::mock(Validate::class);

        $this->assertFalse($this->cloudPresentGroup->isCloudPresentGroup($decoder, $validate));
    }

    public function testSuccessSetCloudPresentGroup()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(true);
        $decoder->shouldReceive('getAmountLowCloudSymbol')->once()->andReturn('1');
        $decoder->shouldReceive('getAmountLowCloud')->once()->andReturn('1 eight of sky covered, or less, but not zero');
        $decoder->shouldReceive('getFormLowCloudSymbol')->once()->andReturn('4');
        $decoder->shouldReceive('getFormLowCloud')->once()->andReturn('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present');
        $decoder->shouldReceive('getFormMediumCloudSymbol')->once()->andReturn('4');
        $decoder->shouldReceive('getFormMediumCloud')->once()->andReturn('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance');
        $decoder->shouldReceive('getFormHighCloudSymbol')->once()->andReturn('9');
        $decoder->shouldReceive('getFormHighCloud')->once()->andReturn('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant');

        $validate = Mockery::mock(Validate::class);

        $this->cloudPresentGroup->setCloudPresentGroup($decoder, $validate);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $propertyAlcs = $reflector->getProperty('amountLowCloudSymbol');
        $propertyAlcs->setAccessible(true);
        $valueAlcs = $propertyAlcs->getValue($this->cloudPresentGroup);

        $propertyAlc = $reflector->getProperty('amountLowCloud');
        $propertyAlc->setAccessible(true);
        $valueAlc = $propertyAlc->getValue($this->cloudPresentGroup);

        $propertyLcs = $reflector->getProperty('formLowCloudSymbol');
        $propertyLcs->setAccessible(true);
        $valueLcs = $propertyLcs->getValue($this->cloudPresentGroup);

        $propertyFlc = $reflector->getProperty('formLowCloud');
        $propertyFlc->setAccessible(true);
        $valueFlc = $propertyFlc->getValue($this->cloudPresentGroup);

        $propertyFmcs = $reflector->getProperty('formMediumCloudSymbol');
        $propertyFmcs->setAccessible(true);
        $valueFmcs = $propertyFmcs->getValue($this->cloudPresentGroup);

        $propertyFmc = $reflector->getProperty('formMediumCloud');
        $propertyFmc->setAccessible(true);
        $valueFmc = $propertyFmc->getValue($this->cloudPresentGroup);

        $propertyFhcs = $reflector->getProperty('formHighCloudSymbol');
        $propertyFhcs->setAccessible(true);
        $valueFhcs = $propertyFhcs->getValue($this->cloudPresentGroup);

        $propertyFhc = $reflector->getProperty('formHighCloud');
        $propertyFhc->setAccessible(true);
        $valueFhc = $propertyFhc->getValue($this->cloudPresentGroup);

        $expected = [
            '1',
            '1 eight of sky covered, or less, but not zero',
            '4',
            'Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present',
            '4',
            'Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance',
            '9',
            'Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant'
        ];

        $this->assertEquals($expected, [$valueAlcs, $valueAlc, $valueLcs, $valueFlc, $valueFmcs, $valueFmc, $valueFhcs, $valueFhc]);
    }

    public function testSNullSetCloudPresentGroup()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $decoder->shouldReceive('isGroup')->once()->andReturn(false);

        $validate = Mockery::mock(Validate::class);

        $this->cloudPresentGroup->setCloudPresentGroup($decoder, $validate);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $propertyAlcs = $reflector->getProperty('amountLowCloudSymbol');
        $propertyAlcs->setAccessible(true);
        $valueAlcs = $propertyAlcs->getValue($this->cloudPresentGroup);

        $propertyAlc = $reflector->getProperty('amountLowCloud');
        $propertyAlc->setAccessible(true);
        $valueAlc = $propertyAlc->getValue($this->cloudPresentGroup);

        $propertyLcs = $reflector->getProperty('formLowCloudSymbol');
        $propertyLcs->setAccessible(true);
        $valueLcs = $propertyLcs->getValue($this->cloudPresentGroup);

        $propertyFlc = $reflector->getProperty('formLowCloud');
        $propertyFlc->setAccessible(true);
        $valueFlc = $propertyFlc->getValue($this->cloudPresentGroup);

        $propertyFmcs = $reflector->getProperty('formMediumCloudSymbol');
        $propertyFmcs->setAccessible(true);
        $valueFmcs = $propertyFmcs->getValue($this->cloudPresentGroup);

        $propertyFmc = $reflector->getProperty('formMediumCloud');
        $propertyFmc->setAccessible(true);
        $valueFmc = $propertyFmc->getValue($this->cloudPresentGroup);

        $propertyFhcs = $reflector->getProperty('formHighCloudSymbol');
        $propertyFhcs->setAccessible(true);
        $valueFhcs = $propertyFhcs->getValue($this->cloudPresentGroup);

        $propertyFhc = $reflector->getProperty('formHighCloud');
        $propertyFhc->setAccessible(true);
        $valueFhc = $propertyFhc->getValue($this->cloudPresentGroup);

        $expected = [
            null, null, null, null, null, null, null, null,
        ];

        $this->assertEquals($expected, [$valueAlcs, $valueAlc, $valueLcs, $valueFlc, $valueFmcs, $valueFmc, $valueFhcs, $valueFhc]);
    }

    public function testSuccessGetFormHighCloudValue()
    {
        $this->assertEquals('Cirrus, Cirrocumulus and Cirrostartus invisible owing to darkness, fog, blowing dust or sand, or other similar phenomena or more often because of the presence of a continuous layer of lower clouds', $this->cloudPresentGroup->getFormHighCloudValue());
    }

    public function testSuccessIsStringGetFormHighCloudValue()
    {
        $this->assertIsString($this->cloudPresentGroup->getFormHighCloudValue());
    }

    public function testSuccessGetFormHighCloudSymbolValue()
    {
        $this->assertEquals('/', $this->cloudPresentGroup->getFormHighCloudSymbolValue());
    }

    public function testSuccessIsStringGetFormHighCloudSymbolValue()
    {
        $this->assertIsString($this->cloudPresentGroup->getFormHighCloudSymbolValue());
    }

    public function testSuccessGetFormMediumCloudValue()
    {
        $this->assertEquals('Semi-transparent Altocumulus in bands, or Altocumulus in one or more fairly continuous layers (semi-transparent or opaque), progressively invading the sky; these Altocumulus clouds generally thicken as a whole', $this->cloudPresentGroup->getFormMediumCloudValue());
    }

    public function testSuccessIsStringGetFormMediumCloudValue()
    {
        $this->assertIsString($this->cloudPresentGroup->getFormMediumCloudValue());
    }

    public function testSuccessGetFormMediumCloudSymbolValue()
    {
        $this->assertEquals('5', $this->cloudPresentGroup->getFormMediumCloudSymbolValue());
    }

    public function testSuccessIsStringGetFormMediumCloudSymbolValue()
    {
        $this->assertIsString($this->cloudPresentGroup->getFormMediumCloudSymbolValue());
    }

    public function testSuccessGetFormLowCloudValue()
    {
        $this->assertEquals('Stratocumulus not resulting from the spreading out of Cumulus', $this->cloudPresentGroup->getFormLowCloudValue());
    }

    public function testSuccessIsStringGetFormLowCloudValue()
    {
        $this->assertIsString($this->cloudPresentGroup->getFormLowCloudValue());
    }

    public function testSuccessGetFormLowCloudSymbolValue()
    {
        $this->assertEquals('5', $this->cloudPresentGroup->getFormLowCloudSymbolValue());
    }

    public function testSuccessGetAmountLowCloudValue()
    {
        $this->assertEquals('2 eight of sky covered', $this->cloudPresentGroup->getAmountLowCloudValue());
    }

    public function testSuccessGetDecoder()
    {
        $this->assertInstanceOf(GroupDecoderInterface::class, $this->cloudPresentGroup->getDecoder());
    }

    public function testSuccessSetFormHighCloudValue()
    {
        $this->cloudPresentGroup->setFormHighCloudValue('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant', $value);
    }

    public function testSuccessIsStringSetFormHighCloudValue()
    {
        $this->cloudPresentGroup->setFormHighCloudValue('Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetFormHighCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormHighCloudSymbolValue('9');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('9', $value);
    }

    public function testSuccessIsStringSetFormHighCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormHighCloudSymbolValue('9');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formHighCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetFormMediumCloudValue()
    {
        $this->cloudPresentGroup->setFormMediumCloudValue('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance', $value);
    }

    public function testSuccessIsStringSetFormMediumCloudValue()
    {
        $this->cloudPresentGroup->setFormMediumCloudValue('Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetFormMediumCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormMediumCloudSymbolValue('4');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('4', $value);
    }

    public function testSuccessIStringSetFormMediumCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormMediumCloudSymbolValue('4');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formMediumCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetFormLowCloudValue()
    {
        $this->cloudPresentGroup->setFormLowCloudValue('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present', $value);
    }

    public function testSuccessIsStringSetFormLowCloudValue()
    {
        $this->cloudPresentGroup->setFormLowCloudValue('Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetFormLowCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormLowCloudSymbolValue('4');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('4', $value);
    }

    public function testSuccessIsStringSetFormLowCloudSymbolValue()
    {
        $this->cloudPresentGroup->setFormLowCloudSymbolValue('4');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('formLowCloudSymbol');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetAmountLowCloudValue()
    {
        $this->cloudPresentGroup->setAmountLowCloudValue('1 eight of sky covered, or less, but not zero');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertEquals('1 eight of sky covered, or less, but not zero', $value);
    }

    public function testSuccessIsStringSetAmountLowCloudValue()
    {
        $this->cloudPresentGroup->setAmountLowCloudValue('1 eight of sky covered, or less, but not zero');

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('amountLowCloud');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertIsString($value);
    }

    public function testSuccessSetDecoder()
    {
        $decoder = Mockery::mock(CloudPresentDecoder::class);
        $this->cloudPresentGroup->setDecoder($decoder);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $property = $reflector->getProperty('decoder');
        $property->setAccessible(true);
        $value = $property->getValue($this->cloudPresentGroup);

        $this->assertInstanceOf(GroupDecoderInterface::class, $value);
    }

    public function testSuccessSetData()
    {
        $validate = Mockery::mock(Validate::class);
        $validate->shouldReceive('isValidGroup')->once()->andReturn(true);

        $this->cloudPresentGroup->setData('81449', $validate);

        $reflector = new \ReflectionClass(CloudPresentGroup::class);
        $propertyData = $reflector->getProperty('rawCloudPresent');
        $propertyData->setAccessible(true);
        $valueData = $propertyData->getValue($this->cloudPresentGroup);

        $propertyDec = $reflector->getProperty('decoder');
        $propertyDec->setAccessible(true);
        $valueDec = $propertyDec->getValue($this->cloudPresentGroup);

        $propertyAlcs = $reflector->getProperty('amountLowCloudSymbol');
        $propertyAlcs->setAccessible(true);
        $valueAlcs = $propertyAlcs->getValue($this->cloudPresentGroup);

        $propertyAlc = $reflector->getProperty('amountLowCloud');
        $propertyAlc->setAccessible(true);
        $valueAlc = $propertyAlc->getValue($this->cloudPresentGroup);

        $propertyLcs = $reflector->getProperty('formLowCloudSymbol');
        $propertyLcs->setAccessible(true);
        $valueLcs = $propertyLcs->getValue($this->cloudPresentGroup);

        $propertyFlc = $reflector->getProperty('formLowCloud');
        $propertyFlc->setAccessible(true);
        $valueFlc = $propertyFlc->getValue($this->cloudPresentGroup);

        $propertyFmcs = $reflector->getProperty('formMediumCloudSymbol');
        $propertyFmcs->setAccessible(true);
        $valueFmcs = $propertyFmcs->getValue($this->cloudPresentGroup);

        $propertyFmc = $reflector->getProperty('formMediumCloud');
        $propertyFmc->setAccessible(true);
        $valueFmc = $propertyFmc->getValue($this->cloudPresentGroup);

        $propertyFhcs = $reflector->getProperty('formHighCloudSymbol');
        $propertyFhcs->setAccessible(true);
        $valueFhcs = $propertyFhcs->getValue($this->cloudPresentGroup);

        $propertyFhc = $reflector->getProperty('formHighCloud');
        $propertyFhc->setAccessible(true);
        $valueFhc = $propertyFhc->getValue($this->cloudPresentGroup);

        $expected = [
            '81449',
            true,
            '1',
            '1 eight of sky covered, or less, but not zero',
            '4',
            'Stratocumulus formed by the spreading out of Cumulus; Cumulus also may be present',
            '4',
            'Patches (often in the form of almonds or fishes) of Altocumulus the greater part of which is semi-transparent; the clouds occur at one or more levels and the elements are continually changing in appearance',
            '9',
            'Cirrostratus alone, or Cirrocumulus accompanied by Cirrus or Cirrostratus, or both, but Cirrocumulus is predominant'
        ];

        $this->assertEquals($expected, [$valueData, $valueDec instanceof CloudPresentDecoder, $valueAlcs, $valueAlc, $valueLcs, $valueFlc, $valueFmcs, $valueFmc, $valueFhcs, $valueFhc]);
    }

    public function testExceptionSetData()
    {
        $validate = Mockery::mock(Validate::class);

        $this->expectException(\Exception::class);

        $this->cloudPresentGroup->setData('', $validate);
    }

    public function testSuccessGetGroupIndicator()
    {
        $this->assertEquals('8NhClCmCH', $this->cloudPresentGroup->getGroupIndicator());
    }

    public function testSuccessIsStringGetGroupIndicator()
    {
        $this->assertIsString($this->cloudPresentGroup->getGroupIndicator());
    }
}

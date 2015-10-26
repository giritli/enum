<?php

use Giritli\Tests\Enum\StatusEnum;
use Giritli\Tests\Enum\StatusNoDefaultEnum;
use Giritli\Enum\Exception\ValueNotFoundException;

class EnumTest extends PHPUnit_Framework_TestCase
{
	public function testEnumValue()
	{
		$enum = new StatusEnum();
		$this->assertSame($enum->getValue(), 'draft');

		$enum = new StatusEnum('draft');
		$this->assertSame($enum->getValue(), 'draft');

		$enum = new StatusEnum(StatusEnum::draft);
		$this->assertSame($enum->getValue(), 'draft');
	}

	public function testEnumCreationWithCallStatic()
	{
		$enum = StatusEnum::draft();
		$this->assertSame($enum->getValue(), 'draft');
		$this->assertEquals($enum, new StatusEnum('draft'));
		$this->assertEquals(StatusEnum::draft()->getValue(), StatusEnum::draft);
	}

	public function testEnumValueOrdinals()
	{
		$enum = new StatusEnum();
		$this->assertSame($enum->getOrdinal(), 0);

		$enum = new StatusEnum('archived');
		$this->assertSame($enum->getOrdinal(), 2);
	}

	public function testAllEnumValues()
	{
		$this->assertSame(StatusEnum::getValues(), [
			'draft' => 'draft',
			'active' => 'active',
			'archived' => 'archived',
			'cancelled' => 'cancelled'
		]);
	}

	public function testAllEnumOrdinals()
	{
		$this->assertSame(StatusEnum::getOrdinals(), [
			'draft' => 0,
			'active' => 1,
			'archived' => 2,
			'cancelled' => 3
		]);
	}

	public function testAllEnumKeys()
	{
		$this->assertSame(StatusEnum::getKeys(), [
			'draft',
			'active',
			'archived',
			'cancelled'
		]);
	}

	public function testStringConversion()
	{
		$this->assertSame((string) new StatusEnum, 'draft');
	}


	/**
	 * @expectedException Giritli\Enum\Exception\ValueNotFoundException
	 */
	public function testEnumValueNotFound()
	{
		$enum = new StatusEnum('unknown-value');
	}


	/**
	 * @expectedException Giritli\Enum\Exception\NoDefaultValueException
	 */
	public function testEnumWithNoDefaultValue()
	{
		$enum = new StatusNoDefaultEnum();
	}

	public function testExtendedEnumInheritsValues()
	{
		$enum = new StatusNoDefaultEnum('draft');
		$this->assertSame((string) $enum, (string) new StatusEnum());

		$enum = new StatusNoDefaultEnum('processing');
		$this->assertSame((string) $enum, 'processing');
	}
}
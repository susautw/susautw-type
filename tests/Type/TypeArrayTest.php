<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/26
 * Time: 上午 12:00
 */

namespace SuRin\Types\Type;

use PHPUnit\Framework\TestCase;
use SuRin\Types\Exception\ClassNotFoundException;
use SuRin\Types\Exception\IncompatibleTypeException;

/**
 * Class TypeArrayTest
 * @package SuRin\Types\Type
 */
class TypeArrayTest extends TestCase
{
	/**
	 * @throws ClassNotFoundException
	 */
	public function testConstructWithType()
	{
		try{
			$arr = new TypeArray("string",["","123","abc",.05]);
			$arr[] = "";
		}catch (IncompatibleTypeException $e){
			self::assertEquals(new IncompatibleTypeException("string","double"),$e);
		}

		try{
			$arr2 = new TypeArray("integer",[1,2,4,5,6,7,true]);
			$arr2[] = 8;
		}catch (IncompatibleTypeException $e){
			self::assertEquals(new IncompatibleTypeException("integer","boolean"),$e);
		}

	}

	/**
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function testConstructWithClass()
	{
		/** @var FakeChild[]|TypeArray $arr */
		$arr = new TypeArray(FakeChild::class,[new FakeChild()]);

		self::assertEquals(FakeChild::class,$arr[0]->get());
	}


	/**
	 * @throws ClassNotFoundException
	 */
	public function testIncompatibleTypeAssign()
	{
		try {
			/** @var string[]|TypeArray $arr */
			$arr = new TypeArray("double");
			$arr[] = "string";
		} catch (IncompatibleTypeException $e) {
			self::assertEquals(new IncompatibleTypeException("double","string"),$e);
		}


	}

	/**
	 * @throws ClassNotFoundException
	 */
	public function testIncompatibleClassAssign()
	{
		/** @var FakeChild[]|TypeArray $arr */
		try{
			$arr = new TypeArray(FakeChild::class);

			$arr[] = new FakeChild2();
		}catch (IncompatibleTypeException $e){
			self::assertEquals(new IncompatibleTypeException(FakeChild::class,FakeChild2::class),$e);
		}
	}

	/**
	 * @throws IncompatibleTypeException
	 */
	public function testClassNotFound()
	{
		try {
			$arr = new TypeArray("FakeNotFound");
			$arr[] = new FakeChild2();
		} catch (ClassNotFoundException $e) {
			self::assertEquals(new ClassNotFoundException("FakeNotFound"),$e);
		}

	}

	/**
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function testAssignChildrenClass()
	{
		/** @var FakeParent[]|TypeArray $arr */
		$arr = new TypeArray(FakeParent::class);

		$arr[] = new FakeChild();
		self::assertEquals(FakeChild::class,$arr[0]->get());
	}

	/**
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function testAssignRealizationClass()
	{
		/** @var FakeInterface[]|TypeArray $arr */
		$arr = new TypeArray(FakeInterface::class);

		$expected = [FakeParent2::class,FakeChild2::class];
		$arr[] = new FakeParent2();
		$arr[] = new FakeChild2();

		$actual = [$arr[0]->get(),$arr[1]->get()];
		self::assertEquals($expected,$actual);
	}

	/**
	 * @throws ClassNotFoundException
	 */
	public function testAssignSuperClass()
	{
		try {
			/** @var FakeChild2[]|TypeArray $arr */
			$arr = new TypeArray(FakeChild2::class);
			$arr[] = new FakeParent2();
		} catch (IncompatibleTypeException $e) {
			self::assertEquals(new IncompatibleTypeException(FakeChild2::class,FakeParent2::class),$e);
		}
	}

	/**
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function testCheckTypeWithType()
	{
		$arr = new TypeArray("string");


		try {
			$arr->checkType("string"); //ok
			$arr->checkType("integer"); //incompatible expected integer actual string
		} catch (IncompatibleTypeException $e) {
			self::assertEquals(new IncompatibleTypeException("integer","string"),$e);
		}
	}

	/**
	 * @throws ClassNotFoundException
	 */
	public function testCheckTypeWithChildrenClass()
	{
		try {
			/** @var FakeParent[]|TypeArray $arr */
			$arr = new TypeArray(FakeParent::class);
			$arr->checkType(FakeChild::class);
		} catch (IncompatibleTypeException $e) {
			self::assertEquals(new IncompatibleTypeException(FakeChild::class,FakeParent::class),$e);
		}
	}

	/**
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function testCheckTypeWithSuperClass()
	{
		/** @var FakeChild2[]|TypeArray $arr */
		$arr = new TypeArray(FakeChild2::class);
		$arr->checkType(FakeParent2::class);
		$arr[] = new FakeChild2();

		self::assertEquals(FakeChild2::class,$arr[0]->get());
	}
}

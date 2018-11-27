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
 *
 * @package SuRin\Types\Type
 */
class TypeArrayTest extends TestCase
{
    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testConstructWithTypeString()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage('Incompatible type : expected string but got double.');

        new TypeArray("string", ["","123","abc",.05]);
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testConstructWithTypeInteger()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage('Incompatible type : expected integer but got boolean.');

        new TypeArray("integer", [1,2,4,5,6,7,true]);
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testConstructWithClass()
    {
        /**
                 * @var FakeChild[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeChild::class, [new FakeChild()]);

        self::assertEquals(FakeChild::class, $arr[0]->get());
    }


    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testIncompatibleTypeAssign()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage('Incompatible type : expected double but got string.');

        /**
                * @var string[]|TypeArray $arr
                */
        $arr = new TypeArray("double");
        $arr[] = "string";
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testIncompatibleClassAssign()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage(
            'Incompatible type : expected ' . FakeChild::class . ' but got ' . FakeChild2::class . '.'
        );

        $arr = new TypeArray(FakeChild::class);

        $arr[] = new FakeChild2();
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testClassNotFound()
    {
        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('Unknown Type : FakeNotFound.');

        $arr = new TypeArray("FakeNotFound");
        $arr[] = new FakeChild2();
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testAssignChildrenClass()
    {
        /**
                * @var FakeParent[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeParent::class);

        $arr[] = new FakeChild();
        self::assertEquals(FakeChild::class, $arr[0]->get());
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testAssignRealizationClass()
    {
        /**
                 * @var FakeInterface[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeInterface::class);

        $expected = [FakeParent2::class,FakeChild2::class];
        $arr[] = new FakeParent2();
        $arr[] = new FakeChild2();

        $actual = [$arr[0]->get(),$arr[1]->get()];
        self::assertEquals($expected, $actual);
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testAssignSuperClass()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage(
            'Incompatible type : expected ' . FakeChild2::class . ' but got ' . FakeParent2::class . '.'
        );

        /**
                 * @var FakeChild2[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeChild2::class);
        $arr[] = new FakeParent2();
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testCheckTypeWithType()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage('Incompatible type : expected integer but got string.');

        $arr = new TypeArray("string");

        $arr->checkType("string"); //ok
        $arr->checkType("integer"); //incompatible expected integer actual string
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testCheckTypeWithChildrenClass()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->expectExceptionMessage(
            'Incompatible type : expected ' . FakeChild::class . ' but got ' . FakeParent::class . '.'
        );

        /**
                 * @var FakeParent[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeParent::class);
        $arr->checkType(FakeChild::class);
    }

    /**
     * @throws ClassNotFoundException
     * @throws IncompatibleTypeException
     */
    public function testCheckTypeWithSuperClass()
    {
        /**
                 * @var FakeChild2[]|TypeArray $arr
                */
        $arr = new TypeArray(FakeChild2::class);
        $arr->checkType(FakeParent2::class);
        $arr[] = new FakeChild2();

        self::assertEquals(FakeChild2::class, $arr[0]->get());
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/25
 * Time: 下午 11:21
 */
namespace SuRin\Types\Type;


use ArrayObject;
use SuRin\Types\Exception\ClassNotFoundException;
use SuRin\Types\Exception\IncompatibleTypeException;

class TypeArray extends ArrayObject {
	private static $primaryTypes = ["boolean","integer","double","string"];
	private $type;

	/**
	 * TypedArray constructor.
	 * @param string $type
	 * @param array $array
	 * @throws ClassNotFoundException
	 * @throws IncompatibleTypeException
	 */
	public function __construct(string $type, array $array = [])
	{
		if($this->typeExists($type) || $this->classExists($type)){
			$this->type = $type;
			foreach ($array as $key=>$value)
				$this->offsetSet($key,$value);
		}else
			throw new ClassNotFoundException($type);
	}

	private function typeExists(string $type):bool
	{
		return in_array($type,self::$primaryTypes);
	}

	private function classExists(string $type):bool
	{
		return class_exists($type) || interface_exists($type);
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @throws IncompatibleTypeException
	 */
	public function offsetSet($offset, $value)
	{
		if($this->typeCorrect($value))
			parent::offsetSet($offset,$value);
		else
			throw new IncompatibleTypeException($this->type,$this->getValueType($value));
	}

	private function typeCorrect($value):bool
	{
		if(gettype($value) == $this->type)
			return true;
		if($value instanceof $this->type)
			return true;
		return false;
	}

	private function getValueType($value):string
	{
		if (is_object($value)) {
			return get_class($value);
		} else {
			return gettype($value);
		}
	}

	/**
	 * @param $expected
	 * @throws IncompatibleTypeException
	 * @throws ClassNotFoundException
	 */
	public function checkType(string $expected):void
	{
		$isClassExists = $this->classExists($expected);

		if($isClassExists || $this->typeExists($expected)){
			if($this->type == $expected)
				return;
			if($isClassExists && $this->isClassParentsOrImplements($expected))
				return;
			throw new IncompatibleTypeException($expected,$this->type);
		}else
			throw new ClassNotFoundException($expected);
	}

	private function isClassParentsOrImplements(string $type):bool
	{
		return in_array($type,class_parents($this->type)) || in_array($type,class_implements($this->type));
	}

}


<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/25
 * Time: 下午 11:25
 */

namespace SuRin\Types\Exception;


use Throwable;

class IncompatibleTypeException extends TypeErrorException
{
	public function __construct(string $type,string $incompatible, int $code = 0, Throwable $previous = null)
	{
		parent::__construct("Incompatible type : need $type but get $incompatible.", $code, $previous);
	}
}
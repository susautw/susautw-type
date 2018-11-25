<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/25
 * Time: 下午 11:25
 */

namespace SuRin\Types\Exception;


use Throwable;

class ClassNotFoundException extends TypeErrorException
{
	public function __construct(string $unknown, int $code = 0, Throwable $previous = null)
	{
		parent::__construct("Unknown Type : $unknown.", $code, $previous);
	}
}
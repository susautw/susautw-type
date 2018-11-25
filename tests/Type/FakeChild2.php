<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/26
 * Time: 上午 12:22
 */

namespace SuRin\Types\Type;


class FakeChild2 extends FakeParent2
{
	public function get():string
	{
		return self::class;
	}

}
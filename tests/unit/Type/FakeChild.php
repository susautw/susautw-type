<?php
/**
 * Created by PhpStorm.
 * User: Su-Rin
 * Date: 2018/11/26
 * Time: 上午 12:21
 */

namespace SuRin\Types\Type;


class FakeChild extends FakeParent
{
    public function get():string
    {
        return self::class;
    }
}

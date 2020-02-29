<?php


namespace SuRin\Types\Type;


use JsonSerializable;

class FakeJsonSerializable implements JsonSerializable
{
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return "json";
    }
}
<?php

namespace PHPFileManipulator\Support\AST;

class Unpacker
{
    const handlerMap = [
        'PhpParser\Node\Expr\Array_' => 'unpackArray',
        'PhpParser\Node\Scalar\String_' => 'unpackString',
    ];

    public static function unpack($ast)
    {
        $class = get_class($ast);

        $handler = static::handlerMap[$class] ?? null;
        if(!$handler) throw new \Exception("Could not find handler $handler");

        return static::$handler($ast);
    }

    protected static function unpackArray($array)
    {
        return collect($array->items)->map(function($item) {
            return Unpacker::unpack($item->value);
        })->toArray();
    }

    protected static function unpackString($string)
    {
        return $string->value;
    }    
}
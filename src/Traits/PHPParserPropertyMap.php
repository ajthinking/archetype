<?php

namespace Archetype\Traits;

trait PHPParserPropertyMap
{
    public function propertyMap(string $property = null)
    {
        $map = [
            'expr',
            'attributes',
            'type',
            'name',
            'alias',
            'vars',
            'stmts',
            'traits',
            'adaptations',
            'insteadof',
            'trait',
            'method',
            'newModifier',
            'newName',
            'types',
            'var',
            'flags',
            'extends',
            'implements',
            'default',
            'cond',
            'num',
            'byRef',
            'params',
            'returnType',
            'magicNames',
            'remaining',
            'key',
            'value',
            'catches',
            'finally',
            'exprs',
            'declares',
            'props',
            'elseifs',
            'else',
            'consts',
            'cases',
            'keyVar',
            'valueVar',
            'init',
            'loop',
            'prefix',
            'use',
            'uses',
            'specialClassNames',
            'variadic',
            'left',
            'right',
            'items',
            'parts',
            'class',
            'args',
            'static',
            'dim',
            'if',
            'unpack',
            'replacements',
        ];

        if (!$property) return $map;

        return in_array($property, $map) ? $property : null;
    }	
}
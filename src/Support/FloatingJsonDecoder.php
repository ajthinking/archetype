<?php

namespace Archetype\Support;

use Archetype\Support\AST\ASTQueryBuilder;
use PhpParser\JsonDecoder;
use Archetype\Support\AST\Visitors\AttributeRemover;
use Arr;

class FloatingJsonDecoder extends JsonDecoder
{
    public function decode($text)
    {
        $decoded = parent::decode($text);

        $result = AttributeRemover::on(
            Arr::wrap($decoded)
        )[0];

        return $result;
    }
}
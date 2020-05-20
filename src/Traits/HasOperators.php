<?php

namespace PHPFileManipulator\Traits;

trait HasOperators
{
    protected function operatorMethod($operator)
    {
        $operators = [
            '='         => 'equals',
            'equals'    => 'equals',
            '!='        => 'notEquals',
            'not'       => 'notEquals',
            'contains'  => 'contains',
            'has'       => 'contains',
            'in'        => 'inOperator',
            'like'      => 'like',
            'matches'   => 'matches',
            'count'     => 'count',
            '>'         => 'greaterThan',
            '<'         => 'lessThan',
        ];

        return $operators[$operator] ?? null;
    }

    protected function equals($candidate, $expected)
    {
        return $candidate == $expected;
    }

    protected function notEquals($candidate, $forbidden)
    {
        return $candidate != $forbidden;
    }

    protected function contains($candidate, $needle)
    {
        return collect($candidate)->contains($needle);
    }

    // Conflict with QueryBuilder::in($path) -> add Operator suffix
    protected function inOperator($candidate, $haystack)
    {
        return collect($haystack)->contains($candidate);
    }

    protected function like($candidate, $like)
    {
        return preg_match("/$like/i", $candidate);
    }

    protected function matches($candidate, $regex)
    {
        return preg_match($regex, $candidate);
    }

    protected function greaterThan($candidate, $length)
    {
        return $candidate > $length;
    }

    protected function lessThan($candidate, $length)
    {
        return $candidate < $length;
    }

    protected function count($candidate, $expected)
    {
        return collect($candidate)->count() == $expected;
    }
}

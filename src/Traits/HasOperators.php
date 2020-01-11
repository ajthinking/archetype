<?php

namespace PHPFileManipulator\Traits;

trait HasOperators
{
    public function equals($candidate, $expected)
    {
        return $candidate == $expected;
    }

    public function notEquals($candidate, $forbidden)
    {
        return $candidate != $forbidden;
    }    

    public function contains($candidate, $needle)
    {
        return collect($candidate)->contains($needle);
    }

    // Conflict with QueryBuilder::in($path) -> add Operator suffix
    public function inOperator($candidate, $haystack)
    {
        return collect($haystack)->contains($candidate);
    }    
    
    public function like($candidate, $like)
    {
        return preg_match("/$like/i", $candidate);
    }
    
    public function matches($candidate, $regex)
    {
        return preg_match($regex, $candidate);
    }
    
    public function greaterThan($candidate, $length)
    {
        return $candidate > $length;
    }
    
    public function lessThan($candidate, $length)
    {
        return $candidate < $length;
    }
    
    public function count($candidate, $expected)
    {
        return collect($candidate)->count() == $expected;
    }    
}
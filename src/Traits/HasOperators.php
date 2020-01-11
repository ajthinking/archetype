<?php

namespace Ajthinking\PHPFileManipulator\Traits;

trait HasOperators
{
    public function equals($candidate, $expected)
    {
        return $candidate == $expected;
    }

    public function contains($candidate, $needle)
    {
        return collect($candidate)->contains($needle);
    }
    
    public function like($candidate, $like)
    {
        return preg_match("/$like/i", $candidate);
    }
    
    public function matches($candidate, $regex)
    {
        return preg_match($regex, $candidate);
    }
    
    public function greaterthan($candidate, $length)
    {
        return $candidate > $length;
    }
    
    public function lessthan($candidate, $length)
    {
        return $candidate < $length;
    }    
}
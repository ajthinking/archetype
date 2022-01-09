<?php

namespace Archetype\Traits;

trait Dumpable
{
    public function dd($target = null)
    {
		if(is_string($target)) {
			
			if(method_exists($this, $target)) dd($this->$target());

			dd($this->$target);
		}		

        dd($this);
    }
}
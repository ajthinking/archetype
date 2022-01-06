<?php

namespace Archetype\Traits;

trait Dumpable
{
    public function dd($property = null)
    {
		if(is_string($property)) {
			dd($this->$property);
		}		

        dd($this);
    }
}
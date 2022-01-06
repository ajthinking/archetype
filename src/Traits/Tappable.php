<?php

namespace Archetype\Traits;

trait Tappable
{
	public function tap($callback)
	{
		$callback($this, $this->currentNodes());

		return $this;
	}	
}
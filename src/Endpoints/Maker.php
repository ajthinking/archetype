<?php

namespace Archetype\Endpoints;

use Archetype\PHPFile;

abstract class Maker extends EndpointProvider
{
	public function withFile(PHPFile $file)
	{
		$this->file = $file;

		return $this;
	}
}
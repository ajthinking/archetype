<?php

use Illuminate\Support\Facades\Config;

use function PHPUnit\Framework\assertFalse;

it('removes debug and output folders at start up', function() {
	assertFalse(
		is_dir(Config::get('archetype.roots.debug.root'))
	);

	assertFalse(
		is_dir(Config::get('archetype.roots.output.root'))
	);
});
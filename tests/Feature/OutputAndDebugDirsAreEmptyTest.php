<?php

use Illuminate\Support\Facades\Config;

it('removes debug and output folders at start up', function() {
	$this->assertFalse(
		is_dir(Config::get('archetype.roots.debug.root'))
	);

	$this->assertFalse(
		is_dir(Config::get('archetype.roots.output.root'))
	);
});
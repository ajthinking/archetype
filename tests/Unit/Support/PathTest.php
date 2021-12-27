<?php

use Archetype\Support\Path;

it('can_create_paths_with_explicit_default_root', function() {
	$relative = Path::make('app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = base_path('app/Models/User.php');
	$this->assertEquals($expected, $relative);

	$absolute = Path::make('/app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = '/app/Models/User.php';

	$this->assertEquals($expected, $absolute);
});

it('can_create_paths_with_assumed_root', function() {
	$expected = '/app/Models/User.php';
	$relative = Path::make('app/Models/User.php')->full();
	$absolute = Path::make('/app/Models/User.php')->full();
	$this->assertEquals($expected, $relative);
	$this->assertEquals($expected, $absolute);
});
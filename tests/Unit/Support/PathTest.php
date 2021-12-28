<?php

use Archetype\Support\Path;

it('can create paths with explicit default root', function() {
	$relative = Path::make('app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = base_path('app/Models/User.php');
	$this->assertEquals($expected, $relative);

	$absolute = Path::make('/app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = '/app/Models/User.php';

	$this->assertEquals($expected, $absolute);
});

it('can create paths with assumed root', function() {
	$expected = '/app/Models/User.php';
	$relative = Path::make('app/Models/User.php')->full();
	$absolute = Path::make('/app/Models/User.php')->full();
	$this->assertEquals($expected, $relative);
	$this->assertEquals($expected, $absolute);
});
<?php

use Archetype\Support\Path;

use function PHPUnit\Framework\assertEquals;

it('can create paths with explicit default root', function() {
	$relative = Path::make('app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = base_path('app/Models/User.php');
	assertEquals($expected, $relative);

	$absolute = Path::make('/app/Models/User.php')->withDefaultRoot(base_path())->full();
	$expected = '/app/Models/User.php';

	assertEquals($expected, $absolute);
});

it('can create paths with assumed root', function() {
	$expected = '/app/Models/User.php';
	$relative = Path::make('app/Models/User.php')->full();
	$absolute = Path::make('/app/Models/User.php')->full();
	assertEquals($expected, $relative);
	assertEquals($expected, $absolute);
});
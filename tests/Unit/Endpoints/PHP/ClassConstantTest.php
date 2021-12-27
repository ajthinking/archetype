<?php

use Archetype\Facades\PHPFile;

it('can_get_a_class_constant', function() {
	$this->assertEquals(
		PHPFile::load('app/Providers/RouteServiceProvider.php')->classConstant('HOME'),
		'/home'
	);
});

it('can_update_existing_class_constants', function() {
        $this->assertEquals(
            PHPFile::load('app/Providers/RouteServiceProvider.php')
			->classConstant('HOME', '/new_home')
			->classConstant('HOME'),
		'/new_home'
	);
});

it('can_create_a_new_class_constant', function() {
        $constant = PHPFile::load('app/Models/User.php')
            ->classConstant('BRAND_NEW', 'it will work')
            ->classConstant('BRAND_NEW');

	$this->assertEquals(
		$constant,
		'it will work'
	);
});

it('can_remove_an_existing_class_constant', function() {
		$this->assertNull(
			PHPFile::make()->class('Dummy')
			->classConstant('MSG', 'hi')
			->remove()->classConstant('MSG')
			->classConstant('MSG')
	);		
});

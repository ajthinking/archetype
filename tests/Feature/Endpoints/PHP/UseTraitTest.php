<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve class trait use statements', function () {
	PHPFile::load('app/Models/User.php')
		->assertUseTrait([
			'HasApiTokens',
			'HasFactory',
			'Notifiable',
		]);
});

it('can retrieve class trait use statements with mixed grouping', function () {
	PHPFile::fromString('class X { use A, B; use C; }')
		->assertUseTrait(['A', 'B', 'C']);
});

it('overwrites existing statements when setting a single value', function () {
	PHPFile::fromString('class X { use A, B; use C; }')
		->useTrait('NewTrait')
		->assertUseTrait(['NewTrait']); //->assertBeautifulPhp();
});

it('overwrites existing statements when setting many values', function () {
	PHPFile::fromString('class X { use A, B; use C; }')
		->useTrait(['D', 'E'])
		->assertUseTrait(['D', 'E']); //->assertBeautifulPhp();
});

it('can add class use trait statements by unshifting (!)', function () {
	PHPFile::fromString('class X { use A; }')
		->add()->useTrait('B')
		->assertUseTrait(['B', 'A']); //->assertBeautifulPhp();
});

it('can add multiple class use trait statements', function () {
	PHPFile::fromString('class X { use A; }')
		->add()->useTrait(['B', 'C'])
		->assertUseTrait(['B', 'C', 'A']); //->assertBeautifulPhp();
});

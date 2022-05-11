<?php

use function Archetype\model;
use function PHPUnit\Framework\assertInstanceOf;

it('returns a LaravelFile', function() {
	assertInstanceOf(
		\Archetype\LaravelFile::class,
		model(\App\Models\User::class)
	);
});
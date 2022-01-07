<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve use statements', function () {
	// A file with use statements
	$file = PHPFile::load('app/Models/User.php');
	$useStatements = $file->use();
	$expectedUseStatements = collect([
		"Illuminate\Notifications\Notifiable",
		"Illuminate\Contracts\Auth\MustVerifyEmail",
		"Illuminate\Foundation\Auth\User as Authenticatable",
	]);

	$expectedUseStatements->each(function ($expectedUseStatement) use ($useStatements) {
		$this->assertTrue(
			collect($useStatements)->contains($expectedUseStatement)
		);
	});

	// A file without use statements
	$file = PHPFile::load('public/index.php');
	$useStatements = $file->use();

	$this->assertTrue(
		collect($useStatements)->count() === 2
	);
});

it('can add use statements in a namespace', function () {
	// on a file with use statements
	$file = PHPFile::load('app/Models/User.php');

	$useStatements = $file->add()->use(['Add\This'])->use();

	$expectedUseStatements = collect([
		"Illuminate\Notifications\Notifiable",
		"Illuminate\Contracts\Auth\MustVerifyEmail",
		"Illuminate\Foundation\Auth\User as Authenticatable",
		"Add\This",
	]);

	$expectedUseStatements->each(function ($expectedUseStatement) use ($useStatements) {
		$this->assertTrue(
			collect($useStatements)->contains($expectedUseStatement)
		);
	});
});

it('can add use statements when not in a namespace', function () {
	$file = PHPFile::load('public/index.php');
	
	$useStatements = $file->add()->use(['Add\This'])->use();
	
	$expectedUseStatements = collect([
		"Add\This",
	]);

	$expectedUseStatements->each(function ($expectedUseStatement) use ($useStatements) {
		$this->assertTrue(
			collect($useStatements)->contains($expectedUseStatement)
		);
	});
});

it('can add use statements with alias', function () {
	$file = PHPFile::load('public/index.php');
	$useStatements = $file->add()->use(['Add\This as Wow'])->use();
	$expectedUseStatements = collect([
		"Add\This as Wow",
	]);
	
	$expectedUseStatements->each(function ($expectedUseStatement) use ($useStatements) {
		$this->assertTrue(
			collect($useStatements)->contains($expectedUseStatement)
		);
	});
});

it('can overwrite use statements', function () {
	$file = PHPFile::load('app/Models/User.php');

	$useStatements = $file->use(['Only\This'])->use();
	$expectedUseStatements = collect([
		"Only\This",
	]);

	$this->assertCount(1, collect($useStatements));

	$this->assertEquals(
		$useStatements->first(),
		'Only\This'
	);
});
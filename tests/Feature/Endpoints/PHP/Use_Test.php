<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve use statements', function () {
	PHPFile::load('app/Models/User.php')
		->assertUse([
			'Illuminate\Contracts\Auth\MustVerifyEmail',
			'Illuminate\Database\Eloquent\Factories\HasFactory',
			'Illuminate\Foundation\Auth\User as Authenticatable',
			'Illuminate\Notifications\Notifiable',
			'Laravel\Sanctum\HasApiTokens',
		]);
});

it('can attempt to retrieve use statements', function() {
	PHPFile::make()->class()
		->assertUse([]);
});

it('can add use statements in a namespace', function () {
	PHPFile::load('app/Models/User.php')
		->add()->use(['Add\This'])
		->assertUse([
			"Add\This",			
			'Illuminate\Contracts\Auth\MustVerifyEmail',
			'Illuminate\Database\Eloquent\Factories\HasFactory',
			'Illuminate\Foundation\Auth\User as Authenticatable',
			'Illuminate\Notifications\Notifiable',
			'Laravel\Sanctum\HasApiTokens',
		])
		->assertValidPhp()
		->assertBeautifulPhp();		
});

it('can add use statements when not in a namespace', function () {
	PHPFile::make()->file()
		->add()->use(['Add\This'])
		->assertUse([
			'Add\This',
		])
		->assertValidPhp()
		->assertBeautifulPhp();		
});

it('can add use statements with alias', function () {
	PHPFile::load('public/index.php')
		->add()->use(['Add\This as Wow'])
		->assertUse([
			"Add\This as Wow",
			'Illuminate\Contracts\Http\Kernel',
			'Illuminate\Http\Request',
		])
		->assertValidPhp()
		->assertBeautifulPhp();		
});

it('can overwrite use statements', function () {
	PHPFile::load('app/Models/User.php')
		->use(['Only\This'])
		->assertUse([
		"Only\This",
	])
	->assertValidPhp()
	->assertBeautifulPhp();	
});
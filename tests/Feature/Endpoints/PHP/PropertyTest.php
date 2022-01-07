<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can get a class property', function() {
	PHPFile::load('app/Models/User.php')
		->assertProperty('fillable', ['name', 'email', 'password']);
});

it('can update existing class properties', function() {
	PHPFile::load('app/Models/User.php')
		->property('fillable', 'new value!')
		->assertProperty('fillable', 'new value!')
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can create a new class property', function() {
	PHPFile::load('app/Models/User.php')
		->property('master', 'yoda')
		->assertProperty('master', 'yoda')
		->assertValidPhp();
		//->assertBeautifulPhp();		
});
	
it('can create a new class property when empty', function() {
	PHPFile::make()->class('Dummy')
		->property('master', 'yoda')
		->assertProperty('master', 'yoda')
		->assertValidPhp()
		->assertBeautifulPhp();
});
	
it('can set empty property by using explicit set method', function() {
	PHPFile::make()->class('Dummy')
		->setProperty('empty')
		->assertProperty('empty', null)
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can set visibility using directives', function() {
	PHPFile::make()->class('Dummy')
		->private()->setProperty('parts')
		->assertContains('private $parts;')
		->assertValidPhp()
		->assertBeautifulPhp();
});
	
it('can remove properties', function() {
	PHPFile::load('app/Models/User.php')
		->remove()->property('fillable')
		->assertNoProperty('fillable')
		->assertValidPhp()
		->assertBeautifulPhp();
});
	
it('can clear properties', function() {
	PHPFile::load('app/Models/User.php')
		->clear()->property('fillable')
		->assertProperty('fillable', null)
		->assertValidPhp()
		->assertBeautifulPhp();
});

it('can empty properties', function() {
	PHPFile::load('app/Models/User.php')
		->empty()->property('fillable')
		->assertProperty('fillable', [])
		->assertValidPhp()
		->assertBeautifulPhp();
});
	
it('can empty string properties', function() {
	PHPFile::load('app/Models/User.php')
		->property('someString', 'hiya')
		->empty()->property('someString')
		->assertProperty('someString', '')
		->assertValidPhp();
		//->assertBeautifulPhp();
});

it('can empty non array or string properties into a default of null', function() {
	PHPFile::load('app/Models/User.php')
		->property('someNonArrayOrStringType', 123)
		->empty()->property('someNonArrayOrStringType')
		->assertProperty('someNonArrayOrStringType', null)
		->assertValidPhp();
		//->assertBeautifulPhp();
});
	
it('can add to array properties', function() {
	PHPFile::load('app/Models/User.php')
		->add()->property('fillable', 'cool')
		->assertProperty('fillable', ['name', 'email', 'password', 'cool'])
		->assertValidPhp();
		//->assertBeautifulPhp();
});

it('can add to string properties', function() {
	PHPFile::load('app/Models/User.php')
		->property('table', 'users')
		->add()->property('table', '_backup')
		->assertProperty('table', 'users_backup')
		->assertValidPhp();
		//->assertBeautifulPhp();
});

it('can add to numeric properties', function() {
	PHPFile::load('app/Models/User.php')
		->property('allowed_errors', 1)
		->add()->property('allowed_errors', 99)
		->assertProperty('allowed_errors', 100)
		->assertValidPhp();
		//->assertBeautifulPhp();
});

it('will default to add to an array if a null property is encountered', function() {
	PHPFile::load('app/Models/User.php')
		->setProperty('realms', null)
		->add()->property('realms', 'Gondor')
		->assertProperty('realms', ['Gondor'])
		->assertValidPhp();
		// ->assertBeautifulPhp();
});

it('will default to add to an array if a non value property is encountered', function() {
	PHPFile::load('app/Models/User.php')
		->setProperty('realms')
		->add()->property('realms', 'Atlantis')
		->assertProperty('realms', ['Atlantis'])
		->assertValidPhp();
		// ->assertBeautifulPhp();
});
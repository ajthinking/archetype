<?php

use Archetype\Facades\PHPFile;

it('can_get_a_class_property', function() {
	$property = PHPFile::load('app/Models/User.php')->property('fillable');

	$this->assertTrue(
		is_array($property)
	);
});

it('can_update_existing_class_properties', function() {
	$newValue = 'Reset fillable to a single string!';
	$property = PHPFile::load('app/Models/User.php')
		->property('fillable', $newValue)
		->property('fillable');

	$this->assertEquals(
		$property,
		$newValue
	);
});

it('can_create_a_new_class_property', function() {
	$property = PHPFile::load('app/Models/User.php')
		->property('master', 'yoda')
		->property('master');

	$this->assertEquals(
		$property,
		'yoda'
	);
});
	
it('can_create_a_new_class_property_when_empty', function() {
	$property = PHPFile::make()->class('Dummy')
		->property('master', 'yoda')
		->property('master');

	$this->assertEquals(
		$property,
		'yoda'
	);
});
	
it('can_set_empty_property_by_using_explicit_set_method', function() {
	$property = PHPFile::make()->class('Dummy')
		->setProperty('empty')
		->property('empty');

	$this->assertEquals(
		$property,
		null
	);
});

it('can_set_visibility_using_directives', function() {
	$output = PHPFile::make()->class('Dummy')
		->private()->setProperty('parts')
		->render();

	$this->assertStringContainsString(
		'private $parts;',
		$output
	);
});
	
it('can_remove_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->remove()->property('fillable')
		->property('fillable');

	$this->assertNull($output);
});
	
it('can_clear_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->clear()->property('fillable')
		->property('fillable');

	$this->assertNull($output);
});

it('can_empty_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->empty()->property('fillable')
		->property('fillable');

		$this->assertEquals($output, []);
});
	
it('can_empty_string_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->property('someString', 'hiya')
		->empty()->property('someString')
		->property('someString');

	$this->assertEquals($output, '');
});

it('can_empty_non_array_or_string_properties_into_a_default_of_null', function() {
	$output = PHPFile::load('app/Models/User.php')
		->property('someNonArrayOrStringType', 123)
		->empty()->property('someNonArrayOrStringType')
		->property('someNonArrayOrStringType');

	$this->assertNull($output);
});
	
it('can_add_to_array_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->add()->property('fillable', 'cool')
		->property('fillable');

	$this->assertEquals(['name', 'email', 'password', 'cool'], $output);
});

it('can_add_to_string_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->property('table', 'users')
		->add()->property('table', '_backup')
		->property('table');

	$this->assertEquals('users_backup', $output);
});

it('can_add_to_numeric_properties', function() {
	$output = PHPFile::load('app/Models/User.php')
		->property('allowed_errors', 1)
		->add()->property('allowed_errors', 99)
		->property('allowed_errors');

	$this->assertEquals(100, $output);
});

it('will_default_to_add_to_an_array_if_null_or_non_value_property_is_encountered', function() {
	$output = PHPFile::load('app/Models/User.php')
		->setProperty('realms')
		->add()->property('realms', 'Atlantis')
		->property('realms');

	$this->assertEquals(['Atlantis'], $output);

	$output = PHPFile::load('app/Models/User.php')
		->setProperty('realms', null)
		->add()->property('realms', 'Gondor')
		->property('realms');

	$this->assertEquals(['Gondor'], $output);
});
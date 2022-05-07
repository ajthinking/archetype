<?php

use Archetype\Facades\PHPFile;

it('outputs no errors on a default laravel installation', function() {
	$this->artisan('archetype:errors') // @phpstan-ignore-line
		->assertSuccessful()
		->expectsOutput("No errors found!");
});

it('outputs erroneous files in a table', function() {
	$path = PHPFile::load('app/Models/User.php')->input->absolutePath();
	file_put_contents($path, '<?php ¯\_(ツ)_/¯;');

	$this->artisan('archetype:errors') // @phpstan-ignore-line
		->assertSuccessful()
		->expectsTable([
			'path',
			'message',
		], [
			[$path, 'Syntax error, unexpected T_STRING on line 1'],
		]);
});
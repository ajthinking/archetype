<?php

use Archetype\Facades\PHPFile;

it('will remember directives when chained', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove();

	$this->assertEquals(
		['add' => true, 'remove' => true],
		$file->directives(),
	);
});
    
it('will forget directives on continue', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove()->continue();
	$this->assertEmpty(
		$file->directives()
	);
});
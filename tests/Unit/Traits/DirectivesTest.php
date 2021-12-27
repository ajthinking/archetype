<?php

use Archetype\Facades\PHPFile;

it('will_remember_directives_when_chained', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove();

	$this->assertEquals(
		['add' => true, 'remove' => true],
		$file->directives(),
	);
});
    
it('will_forget_directives_on_continue', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove()->continue();
	$this->assertEmpty(
		$file->directives()
	);
});
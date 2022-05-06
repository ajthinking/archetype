<?php

use Archetype\Facades\PHPFile;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;

it('will remember directives when chained', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove();

	assertEquals(
		['add' => true, 'remove' => true],
		$file->directives(),
	);
});
    
it('will forget directives on continue', function () {
	$file = PHPFile::load('app/Models/User.php')->add()->remove()->continue();
	assertEmpty(
		$file->directives()
	);
});
<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can retrieve class method names', function() {
	PHPFile::load('app/Console/Kernel.php')
		->assertMethodNames(['schedule', 'commands']);
});

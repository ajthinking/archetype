<?php

use Archetype\Facades\PHPFile;

it('can get trait names', function() {
	$this->assertEquals(
		PHPFile::load('app/Models/User.php')->trait(), // Seems strange
		['HasApiTokens', 'HasFactory', 'Notifiable']
	);
});
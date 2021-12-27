<?php

use Archetype\Facades\PHPFile;

it('can_get_trait_names', function() {
	$this->assertEquals(
		PHPFile::load('app/Models/User.php')->trait(), // Seems strange
		['HasApiTokens', 'HasFactory', 'Notifiable']
	);
});
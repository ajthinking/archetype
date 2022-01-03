<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

it('can add methods via a closure', function() {
	PHPFile::make()->class('Holder')
		->add()->private()->classMethod('holy', function(string $shit = '') {
			return 'poo';
		})
		->preview();
});
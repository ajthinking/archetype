<?php

namespace Archetype;

use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;

function phpFile($uri) {
	return PHPFile::load($uri);
};

function model($uri) {
	return LaravelFile::load($uri);
};
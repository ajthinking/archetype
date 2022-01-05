<?php

namespace Archetype\Tests\Support\Factories;

use Archetype\Factories\PHPFileFactory;
use Archetype\Tests\Support\TestablePHPFile;

class TestablePHPFileFactory extends PHPFileFactory
{
    const FILE_TYPE = TestablePHPFile::class;

    protected static function __makeFileInstance()
    {
        return parent::__makeFileInstance()
			->directive('addMissingTags', true);
    }	
}

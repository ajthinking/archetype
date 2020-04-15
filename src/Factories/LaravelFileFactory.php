<?php

namespace PHPFileManipulator\Factories;

use PHPFileManipulator\Factories\PHPFileFactory;
use PHPFileManipulator\LaravelFile;

class LaravelFileFactory extends PHPFileFactory
{
    const FILE_TYPE = LaravelFile::class;
}
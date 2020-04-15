<?php

namespace PHPFileManipulator\Drivers;

use PHPFileManipulator\Drivers\InputInterface;

class FileInput implements InputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;

    public function load($path = null)
    {
        //
    }
}
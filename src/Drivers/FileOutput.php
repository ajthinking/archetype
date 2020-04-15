<?php

namespace PHPFileManipulator\Drivers;

use PHPFileManipulator\Drivers\OutputInterface;

class FileOutput implements OutputInterface
{
    public $filename;

    public $extension;

    public $relativeDir;
    
    public function save($path = null)
    {
        //
    }

    public function debug($path = null)
    {
        //
    }    
}
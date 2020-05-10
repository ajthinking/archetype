<?php

namespace PHPFileManipulator\Commands\Demo;

use PHPFileManipulator\LaravelFile;

class CustomizedLaravelFile extends LaravelFile
{
    public function itHasExtras()
    {
        return "Something extra!";
    }
}
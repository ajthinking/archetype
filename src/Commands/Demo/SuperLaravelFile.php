<?php

namespace PHPFileManipulator\Commands\Demo;

use PHPFileManipulator\LaravelFile;

class SuperLaravelFile extends LaravelFile
{
    public function itHasExtras()
    {
        return "Something extra!";
    }
}
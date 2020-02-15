<?php

namespace PHPFileManipulator\Tests; 

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPFile;
use LaravelFile;
use Illuminate\Contracts\Console\Kernel;
use ErrorException;
use Illuminate\Support\Str;

class Epic
{
    public static function imRolling()
    {
        return 'Im epic!';
    }
}

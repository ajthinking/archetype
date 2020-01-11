<?php

namespace PHPFileManipulator\Facades;

use Illuminate\Support\Facades\Facade;

class PHPFile extends Facade {
   protected static function getFacadeAccessor() { return 'PHPFile'; }   
}
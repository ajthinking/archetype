<?php

namespace PHPFileManipulator\Support\AST;

use PHPFileManipulator\Support\AST\Killable;

abstract class Terminator {
    public static function kills($connor)
    {
        return is_object($connor) && $connor instanceof Killable ? true : false;
    }
}
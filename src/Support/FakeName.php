<?php

namespace Ajthinking\PHPFileManipulator\Support;

class FakeName {
    public static function __callStatic($method, $args) {}
    public function __call($method, $args) {}
}
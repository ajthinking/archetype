<?php

namespace PHPFileManipulator\Drivers;

Interface OutputInterface
{
    public function save($path = null, $content);

    public function debug($path = null);
}
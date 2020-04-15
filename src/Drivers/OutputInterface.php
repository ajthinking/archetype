<?php

namespace PHPFileManipulator\Drivers;

Interface OutputInterface
{
    public function save($path = null);

    public function debug($path = null);
}
<?php

namespace Archetype\Drivers;

Interface OutputInterface
{
    public function save($path, $content);

    public function debug($path = null);
}
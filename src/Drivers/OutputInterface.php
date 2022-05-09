<?php

namespace Archetype\Drivers;

interface OutputInterface
{
    public function save(string $path, string $content);

    public function debug($path = null);
}

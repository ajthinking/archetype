<?php

namespace PHPFileManipulator\Traits;

trait ManagesFileMetadata
{
    public function contents($contents = false)
    {
        return $contents ? $this->contents = $contents : $this->contents;
    }

    public function ast($ast = false)
    {
        return $ast ? $this->ast = $ast : $this->ast;
    }     
}
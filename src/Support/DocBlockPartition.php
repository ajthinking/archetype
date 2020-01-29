<?php

namespace PHPFileManipulator\Support;

class DocBlockPartition
{
    public function __construct($docBlock)
    {
        $this->docBlock = $docBlock;
    }

    public function description()
    {
        preg_match('/^\/\*\*\n\*\s(.*)\n\*\s\*\s@/gm',$this->docBlock, $matches); 
        return $matches;
    }
}
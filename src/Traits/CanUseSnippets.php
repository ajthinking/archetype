<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Ajthinking\PHPFileManipulator\Snippets\SnippetFile;

trait CanUseSnippets
{
    public function snippet($name, $replacementPairs = [])
    {
        return SnippetFile::$name($replacementPairs);
    }
}
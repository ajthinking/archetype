<?php

namespace Archetype\Support;

use Archetype\Facades\PHPFile;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeFinder;
use PhpParser\JsonDecoder;

use Archetype\Support\AST\Visitors\FormattingRemover;

class Snippet
{
	public $file;

    public static function __callStatic($name, $args)
    {
        $replacementPairs = $args ? $args[0] : [];
        // Get all files containing snippets
        $containers = PHPFile::in(
            'vendor/ajthinking/archetype/src/snippets'
        )->get()->mapInto(static::class);

        $hasCustoms = is_dir(base_path(config('archetype.snippets_path')));
        if ($hasCustoms) {
            $containers = $containers->concat(PHPFile::in(
                config('archetype.snippets_path')
            )->get()->mapInto(static::class));
        }
        
        
        // Find the first matching node
        $node = $containers->map(function ($container) use ($name) {
            return $container->getNodeByName($name);
        })->filter()->first();

        // If not found
        if (!$node) {
            return null; //throw new InvalidArgumentException("Could not find snippet named $name");
        }

        // Replace and return
        return static::replace($node, $replacementPairs);
    }

    public function __construct($file)
    {
        $this->file = $file;
    }

    public static function replace($node, $replacementPairs)
    {
        // REPLACE IDENTIFIERS
        // REPLACE NAMES
        // REPLACE COMMENTS

        $text = json_encode($node);
        
        // SPLIT UP NAME IN PARTS WHERE NECESSARY!!!

        $text = str_replace(
            collect($replacementPairs)->keys()->toArray(),
            collect($replacementPairs)->values()->toArray(),
            $text
        );

        $node = (new JsonDecoder)->decode($text);

        // Remove attributes that messed with pretty printing
        $node = FormattingRemover::on($node);

        return $node;
    }
    
    protected function getNodeByName($name)
    {
        return collect([
            $this->getMethodByName($name)
            // add more findable types here
        ])->filter()->first();
    }

    public function getMethodByName($requestedName)
    {
        return collect((new NodeFinder)->findInstanceOf(
            $this->file->ast(),
            ClassMethod::class
        ))->filter(function ($node) use ($requestedName) {
            return $node->name->name === $requestedName;
        })->first();
    }
}

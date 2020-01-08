<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeFinder;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name;

trait CanUseSnippets
{
    public function snippet($name, $replacementPairs = [])
    {
        $node = $this->load(
            'packages/Ajthinking/PHPFileManipulator/src/Snippets/defaults.php'
        )->getNodeByName($name);

        // REPLACE IDENTIFIERS
        collect($replacementPairs)->each(function($replacementValue, $toBeReplaced) use($node) {
            collect((new NodeFinder)->findInstanceOf(
                $node, Identifier::class
            ))->each(function($identifier) use($toBeReplaced, $replacementValue) {
                if($identifier->name == $toBeReplaced) {
                    $identifier->name = $replacementValue;
                }
            });            
        });

        // REPLACE NAMES
        // collect($replacementPairs)->each(function($replacementValue, $toBeReplaced) use($node) {
        //     collect((new NodeFinder)->findInstanceOf(
        //         $node, Identifier::class
        //     ))->each(function($identifier) use($toBeReplaced, $replacementValue) {
        //         if($identifier->name == $toBeReplaced) {
        //             $identifier->name = $replacementValue;
        //         }
        //     });            
        // });
        
        // REPLACE COMMENTS

        return $node;
    }
    
    private function getNodeByName($name)
    {
        return collect([
            $this->getMethodByName($name)
            // add more findable types here
        ])->filter()->first();
    }

    public function getMethodByName($requestedName)
    {
        return collect((new NodeFinder)->findInstanceOf(
            $this->ast(),
            ClassMethod::class
        ))->filter(function($node) use($requestedName) {
            return $node->name->name == $requestedName;
        })->first();
    }
}
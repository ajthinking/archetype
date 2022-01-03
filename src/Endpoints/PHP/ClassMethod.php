<?php

namespace Archetype\Endpoints\PHP;

use Archetype\Endpoints\EndpointProvider;
use Archetype\Facades\PHPFile;
use Archetype\Support\Snippet;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node;

class ClassMethod extends EndpointProvider
{
    public function classMethod($name, $definition)
    {
        return $this->add($name, $definition);
    }

    protected function add($name, $function)
    {		
		$factory = new BuilderFactory;
		$node = $factory->method($name)->getNode();
	
		// extract ast from file
		// replace the node data with that from the function

		$r = PHPFile::load(
			debug_backtrace(0, 3)[2]['file']
		)->astQuery()
			->get();

        return $this->file->astQuery()
            ->class()
            ->insertStmt(
				Snippet::___HAS_MANY_METHOD___([
					'___HAS_MANY_METHOD___' => 'a',
					'___TARGET_CLASS___' => 'b',
					'___TARGET_IN_DOC_BLOCK___' => 'c'
				])
            )->commit()
            ->end()
            ->continue();
    }
}

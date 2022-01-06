<?php

namespace Archetype\Support\AST;

use BadMethodCallException;
use Illuminate\Support\Arr;

trait RenderGraphs
{
	public function renderGraphs()
	{
		if(!class_exists(\PHPAstVisualizer\Printer::class)) {
			throw new BadMethodCallException("Method only available in --dev installs");
		}

		$map = [];

		foreach($this->tree as $level => $queryNodes) {
			foreach($queryNodes as $nodeIndex => $node) {

				$ast = Arr::wrap($node->result);
				if(empty($ast)) continue;
				$hash = $ast[0]->__object_hash;
				array_push($map, $hash);
				$id = array_search($hash, $map);
				$parent = $node->parent ?? null;
				$parentHash = $parent ? Arr::wrap($node->parent->result)[0]->__object_hash : null;
				$parentId = $parentHash ? array_search($parentHash, $map) : -1;

				$name = 'level_'.$level
					.'_index_'.$nodeIndex
					.'_id_'.$id
					.($parentId !== -1 ? '_parent_'.$parentId : '');
				
				(new \PHPAstVisualizer\Printer)->print($ast)
					->export('png', __DIR__."/../../../logs/$name.png");
			}
		}

		return $this;
	}	
}
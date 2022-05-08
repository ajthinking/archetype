<?php

namespace Archetype\Support\AST;

use stdClass;

class ASTSimplifier
{
	const ATTRIBUTES_TO_KEEP = [
		'expr',
		'name',
		'stmts',
	];

	public static function simplify($ast) {
		$result = new stdClass;
	
		if(is_object($ast)) {
			$result->__class = get_class($ast);
			foreach ($ast as $key => $node) {
				if($key === 'parts') {
					$result->parts = implode('\\', $node);
				}

				if(!in_array($key, static::ATTRIBUTES_TO_KEEP)) continue;
	
				$result->$key = static::simplify($node);
			}
		}
	
		if(is_array($ast)) {
			return collect($ast)->map(function($node) {
				return static::simplify($node);
			})->all();
		}
	
		return $result;
	}	
}
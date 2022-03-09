<?php

namespace Archetype\Support;

use Illuminate\Support\Str;

class LaravelStrings
{
	public static function hasOneMethodName($target)
	{
		return Str::camel(
			class_basename($target)
		);
	}

	public static function hasManyMethodName($target)
	{
		return Str::camel(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function belongsToMethodName($target)
	{
		return Str::camel(
			class_basename($target)
		);
	}

	public static function belongsToManyMethodName($target)
	{
		return Str::camel(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function hasOneDocBlockName($target)
	{
		return Str::studly(
			class_basename($target)
		);
	}

	public static function hasManyDocBlockName($target)
	{
		return Str::studly(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function belongsToDocBlockName($target)
	{
		return Str::studly(
			class_basename($target)
		);
	}

	public static function belongsToManyDocBlockName($target)
	{
		return Str::studly(
			Str::plural(
				class_basename($target)
			)
		);
	}
}

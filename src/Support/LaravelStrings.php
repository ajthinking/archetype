<?php

namespace Archetype\Support;

use Illuminate\Support\Str;

class LaravelStrings
{
	public static function hasOneMethodName($target): string
	{
		return Str::camel(
			class_basename($target)
		);
	}

	public static function hasManyMethodName($target): string
	{
		return Str::camel(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function belongsToMethodName($target): string
	{
		return Str::camel(
			class_basename($target)
		);
	}

	public static function belongsToManyMethodName($target): string
	{
		return Str::camel(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function hasOneDocBlockName($target): string
	{
		return Str::studly(
			class_basename($target)
		);
	}

	public static function hasManyDocBlockName($target): string
	{
		return Str::studly(
			Str::plural(
				class_basename($target)
			)
		);
	}

	public static function belongsToDocBlockName($target): string
	{
		return Str::studly(
			class_basename($target)
		);
	}

	public static function belongsToManyDocBlockName($target): string
	{
		return Str::studly(
			Str::plural(
				class_basename($target)
			)
		);
	}
}

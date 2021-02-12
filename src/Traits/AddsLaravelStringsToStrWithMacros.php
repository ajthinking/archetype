<?php

namespace Archetype\Traits;

use Illuminate\Support\Str;

trait AddsLaravelStringsToStrWithMacros
{
    private function bootStrMacros()
    {
        Str::macro('hasOneMethodName', function ($target) {
            return static::camel(
                class_basename($target)
            );
        });

        Str::macro('hasManyMethodName', function ($target) {
            return static::camel(
                static::plural(
                    class_basename($target)
                )
            );
        });

        Str::macro('belongsToMethodName', function ($target) {
            return static::camel(
                class_basename($target)
            );
        });

        Str::macro('belongsToManyMethodName', function ($target) {
            return static::camel(
                static::plural(
                    class_basename($target)
                )
            );
        });

        Str::macro('hasOneDocBlockName', function ($target) {
            return static::studly(
                class_basename($target)
            );
        });

        Str::macro('hasManyDocBlockName', function ($target) {
            return static::studly(
                static::plural(
                    class_basename($target)
                )
            );
        });

        Str::macro('belongsToDocBlockName', function ($target) {
            return static::studly(
                class_basename($target)
            );
        });

        Str::macro('belongsToManyDocBlockName', function ($target) {
            return static::studly(
                static::plural(
                    class_basename($target)
                )
            );
        });
    }
}

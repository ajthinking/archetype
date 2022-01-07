<?php

declare(strict_types=1);

use Archetype\Tests\TestCase;

if (!function_exists('context')) {
    function context(string $description, Closure $closure)
    {
        $closure();
    }
}

if (!function_exists('describe')) {
    function describe(string $description, Closure $closure)
    {
        $closure();
    }
}

uses(TestCase::class)->in(__DIR__);
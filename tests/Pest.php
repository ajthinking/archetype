<?php

declare(strict_types=1);

use Archetype\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// The console writes in place, so its tests run against an application whose
// output root is the application itself rather than the isolated `.output`
// directory the rest of the suite writes to.
uses()->beforeEach(function () {
    config(['archetype.roots.output.root' => base_path()]);
})->in(__DIR__.'/Feature/Console');

<?php

use Archetype\Console\Support\Manifest;
use Archetype\Tests\Support\Console;

it('lists every operation in one answer', function () {
    $result = Console::run('archetype');

    expect($result->succeeded())->toBeTrue();

    foreach (array_keys(Manifest::OPERATIONS) as $operation) {
        expect($result->output)->toContain($operation);
    }
});

it('describes the operations as json', function () {
    $payload = Console::run('archetype --json')->json();

    expect($payload['operations'])->toHaveCount(count(Manifest::OPERATIONS));
    expect($payload['operations'][0])->toHaveKeys(['operation', 'usage', 'description']);
});

it('registers every command the manifest names', function () {
    foreach (Manifest::commands() as $class) {
        expect(class_exists($class))->toBeTrue("missing $class");
    }

    $registered = array_keys(Illuminate\Support\Facades\Artisan::all());

    foreach (array_keys(Manifest::OPERATIONS) as $operation) {
        expect($registered)->toContain("archetype:$operation");
    }
});

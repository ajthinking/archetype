<?php

use Archetype\Console\Support\Manifest;
use Archetype\Tests\Support\Console;
use Illuminate\Support\Facades\Artisan;

it('lists every operation in one answer', function () {
    $result = Console::run('archetype');

    expect($result->succeeded())->toBeTrue();

    foreach (Manifest::operations() as $operation) {
        expect($result->output)->toContain($operation);
    }
});

it('separates the endpoints from the console additions', function () {
    $output = Console::run('archetype')->output;

    expect($output)->toContain('These are the PHP API endpoints. Give a value to write, none to read.');
    expect($output)->toContain('These have no PHP equivalent.');

    // The rule the naming follows has to be visible, or it is not a rule.
    expect(strpos($output, 'property'))->toBeLessThan(strpos($output, 'These have no PHP equivalent.'));
    expect(strpos($output, 'set-array-key'))->toBeGreaterThan(strpos($output, 'These have no PHP equivalent.'));
});

it('describes the operations as json', function () {
    $payload = Console::run('archetype --json')->json();

    expect($payload['operations'])->toHaveCount(count(Manifest::operations()));
    expect($payload['operations'][0])->toHaveKeys(['operation', 'usage', 'description', 'kind']);
    expect(collect($payload['operations'])->firstWhere('operation', 'property')['kind'])->toBe('endpoint');
    expect(collect($payload['operations'])->firstWhere('operation', 'inspect')['kind'])->toBe('console');
});

it('registers a command for every operation it lists', function () {
    $registered = array_keys(Artisan::all());

    foreach (Manifest::operations() as $operation) {
        expect($registered)->toContain("archetype:$operation");
    }
});

it('names every endpoint command after a real endpoint', function () {
    $php = get_class_methods(Archetype\LaravelFile::class);

    foreach (array_keys(Manifest::ENDPOINTS) as $operation) {
        if (in_array($operation, ['errors'], true)) {
            continue;
        }

        expect(in_array($operation, $php, true))
            ->toBeTrue("$operation is listed as an endpoint but LaravelFile has no such method");
    }
});

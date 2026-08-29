<?php

use Archetype\Tests\Support\Console;

it('lists the files under a directory', function () {
    $result = Console::run('archetype:find app/Http/Middleware');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toContain('app/Http/Middleware/Authenticate.php');
    expect($result->lines())->toContain('8 file(s)');
});

it('lists migrations without being told where they live', function () {
    expect(Console::run('archetype:find --type=migrations')->output)
        ->toContain('database/migrations/2014_10_12_000000_create_users_table.php');
});

it('narrows by what a class extends', function () {
    $payload = Console::run('archetype:find app --extends=Authenticatable --json')->json();

    expect($payload['files'])->toBe(['app/Models/User.php']);
});

it('narrows by trait', function () {
    $payload = Console::run('archetype:find app --uses-trait=HasFactory --json')->json();

    expect($payload['files'])->toBe(['app/Models/User.php']);
});

it('narrows by path', function () {
    $payload = Console::run('archetype:find app --matching=Middleware --json')->json();

    expect($payload['count'])->toBe(8);
});

it('rejects a type it does not have', function () {
    $result = Console::run('archetype:find app --type=nonsense');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain("unknown --type 'nonsense'");
});

it('rejects a directory that is not one', function () {
    $result = Console::run('archetype:find app/Models/User.php');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('is not a directory');
});

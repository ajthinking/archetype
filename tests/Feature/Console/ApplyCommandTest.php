<?php

use Archetype\Tests\Support\Console;
use Illuminate\Support\Facades\File;

function script(string $contents): string
{
    $path = base_path('operations.txt');

    File::put($path, $contents);

    return $path;
}

it('runs several operations in one call', function () {
    $result = Console::run('archetype:apply '.script(<<<'TXT'
        # everything this change needs, in one call
        add-to-property app/Models/User.php fillable nickname
        set-casts app/Models/User.php is_admin=boolean
        add-relation app/Models/User.php hasMany Post
        TXT));

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toContain('3 of 3 operations ok');

    expect(Console::read('app/Models/User.php'))
        ->toContain("'nickname',")
        ->toContain("'is_admin' => 'boolean',")
        ->toContain('return $this->hasMany(Post::class);');
});

it('accepts operations written with the prefix', function () {
    $result = Console::run('archetype:apply '.script('archetype:add-to-property app/Models/User.php fillable nickname'));

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toContain('OK app/Models/User.php $fillable +1');
});

it('reports a failing operation and keeps going', function () {
    $result = Console::run('archetype:apply '.script(<<<'TXT'
        add-to-property app/Models/Nope.php fillable slug
        add-to-property app/Models/User.php fillable nickname
        TXT));

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('1 of 2 operations ok');
    expect(Console::read('app/Models/User.php'))->toContain("'nickname',");
});

it('stops at the first failure when asked', function () {
    $result = Console::run('archetype:apply '.script(<<<'TXT'
        add-to-property app/Models/Nope.php fillable slug
        add-to-property app/Models/User.php fillable nickname
        TXT).' --stop-on-failure');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('0 of 1 operations ok');
    expect(Console::read('app/Models/User.php'))->not->toContain('nickname');
});

it('reports each operation as json', function () {
    $payload = Console::run('archetype:apply '.script(<<<'TXT'
        add-to-property app/Models/User.php fillable nickname
        set-casts app/Models/User.php is_admin=boolean
        TXT).' --json')->json();

    expect($payload['ok'])->toBeTrue();
    expect($payload['ran'])->toBe(2);
    expect($payload['results'][0]['operation'])->toBe('add-to-property app/Models/User.php fillable nickname');
    expect(json_decode($payload['results'][0]['output'], true)['changed'])->toBe(1);
});

it('fails on an empty script', function () {
    $result = Console::run('archetype:apply '.script("\n# nothing here\n"));

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('no operations given');
});

it('fails on a script that is not there', function () {
    $result = Console::run('archetype:apply nowhere.txt');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('no such file: nowhere.txt');
});

<?php

use Archetype\Tests\Support\Console;

it('sets a string property', function () {
    Console::run('archetype:set-property app/Models/User.php table gdpr_users');

    expect(Console::read('app/Models/User.php'))->toContain("protected \$table = 'gdpr_users';");
});

it('sets a property from json', function () {
    Console::run('archetype:set-property app/Models/User.php with \'["profile","posts"]\'');

    expect(Console::read('app/Models/User.php'))->toContain("protected \$with = [\n        'profile',\n        'posts',\n    ];");
});

it('honours the visibility asked for', function () {
    Console::run('archetype:set-property app/Models/User.php perPage 25 --visibility=public');

    expect(Console::read('app/Models/User.php'))->toContain('public $perPage = 25;');
});

it('declares a property with no default when no value is given', function () {
    Console::run('archetype:set-property app/Models/User.php connection');

    expect(Console::read('app/Models/User.php'))->toContain('protected $connection;');
});

it('rejects a visibility that is not one', function () {
    $result = Console::run('archetype:set-property app/Models/User.php table x --visibility=internal');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('--visibility must be public, protected or private');
});

it('appends only the values that are missing', function () {
    $result = Console::run('archetype:add-to-property app/Models/User.php fillable name nickname');

    expect($result->lines()[0])->toBe('OK app/Models/User.php $fillable +1');
    expect(Console::read('app/Models/User.php'))->toContain("'nickname',");
});

it('creates an array property that was not there', function () {
    Console::run('archetype:add-to-property app/Models/User.php appends full_name');

    expect(Console::read('app/Models/User.php'))->toContain("protected \$appends = [\n        'full_name',\n    ];");
});

it('empties a property but keeps the declaration and its visibility', function () {
    Console::run('archetype:empty-property app/Models/User.php fillable');

    $source = Console::read('app/Models/User.php');

    expect($source)->toContain('protected $fillable = [];');
    expect($source)->not->toContain("'email',");
});

it('leaves visibility alone unless it is told to change it', function () {
    Console::run('archetype:set-property app/Models/User.php visible \'["id"]\' --visibility=public');
    Console::run('archetype:add-to-property app/Models/User.php visible name');

    expect(Console::read('app/Models/User.php'))->toContain('public $visible');
});

it('removes a property', function () {
    Console::run('archetype:remove-property app/Models/User.php hidden');

    expect(Console::read('app/Models/User.php'))->not->toContain('$hidden');
});

it('reports a property that was never there rather than failing', function () {
    $result = Console::run('archetype:remove-property app/Models/User.php nope');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/User.php no $nope']);
});

<?php

use Archetype\Tests\Support\Console;

it('reads a property, the way the endpoint does', function () {
    $result = Console::run('archetype:property app/Models/User.php table');

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toBe('null');

    expect(Console::run('archetype:property app/Models/User.php fillable')->output)
        ->toBe('["name","email","password"]');
});

it('reads with the $ in the name, since that is how it is written', function () {
    expect(Console::run('archetype:property app/Models/User.php \'$fillable\'')->output)
        ->toBe('["name","email","password"]');
});

it('writes a property when given a value', function () {
    Console::run('archetype:property app/Models/User.php table gdpr_users');

    expect(Console::read('app/Models/User.php'))->toContain("protected \$table = 'gdpr_users';");
});

it('takes json for anything that is not a plain string', function () {
    Console::run('archetype:property app/Models/User.php with \'["profile","posts"]\'');

    expect(Console::read('app/Models/User.php'))
        ->toContain("protected \$with = [\n        'profile',\n        'posts',\n    ];");
});

it('adds to an array property with --add', function () {
    $result = Console::run('archetype:property app/Models/User.php fillable nickname --add');

    expect($result->lines()[0])->toBe('OK app/Models/User.php $fillable added to');
    expect(Console::read('app/Models/User.php'))->toContain("'nickname',");
});

it('adds several at once when given json', function () {
    Console::run('archetype:property app/Models/User.php fillable \'["nickname","avatar"]\' --add');

    expect(Console::read('app/Models/User.php'))
        ->toContain("'nickname',")
        ->toContain("'avatar',");
});

it('skips an --add that is already there', function () {
    $result = Console::run('archetype:property app/Models/User.php fillable name --add');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/User.php $fillable unchanged']);
});

it('empties with --empty and removes with --remove', function () {
    Console::run('archetype:property app/Models/User.php fillable --empty');
    expect(Console::read('app/Models/User.php'))->toContain('protected $fillable = [];');

    Console::run('archetype:property app/Models/User.php hidden --remove');
    expect(Console::read('app/Models/User.php'))->not->toContain('$hidden');
});

it('declares a property without a default with --clear', function () {
    Console::run('archetype:property app/Models/User.php connection --clear');

    expect(Console::read('app/Models/User.php'))->toContain('protected $connection;');
});

it('reports a --remove of something absent rather than failing', function () {
    $result = Console::run('archetype:property app/Models/User.php nope --remove');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/User.php no $nope']);
});

it('takes the visibility directives as flags', function () {
    Console::run('archetype:property app/Models/User.php perPage 25 --public');

    expect(Console::read('app/Models/User.php'))->toContain('public $perPage = 25;');
});

it('leaves visibility alone unless a flag says otherwise', function () {
    Console::run('archetype:property app/Models/User.php visible \'["id"]\' --public');
    Console::run('archetype:property app/Models/User.php visible name --add');

    expect(Console::read('app/Models/User.php'))->toContain('public $visible');
});

it('refuses two directives that contradict each other', function () {
    $result = Console::run('archetype:property app/Models/User.php fillable --add --remove');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('only one of --add, --remove at a time');
});

it('refuses two visibilities at once', function () {
    $result = Console::run('archetype:property app/Models/User.php table x --public --private');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('only one of --public, --private at a time');
});

it('reads the same property across a directory', function () {
    $result = Console::run('archetype:property app/Models fillable');

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toBe('app/Models/User.php ["name","email","password"]');
});

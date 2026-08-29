<?php

use Archetype\Tests\Support\Console;

it('answers with a diff of what it changed', function () {
    $result = Console::run('archetype:fillable app/Models/User.php nickname --add');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Models/User.php $fillable added to');
    expect($result->output)->toContain('@@ ');
    expect($result->output)->toContain("+         'nickname',");
});

it('suppresses the diff when asked', function () {
    $result = Console::run('archetype:fillable app/Models/User.php nickname --add --no-diff');

    expect($result->lines())->toBe(['OK app/Models/User.php $fillable added to']);
});

it('skips work already done instead of failing', function () {
    $first = Console::run('archetype:fillable app/Models/User.php nickname --add');
    $second = Console::run('archetype:fillable app/Models/User.php nickname --add');

    expect($first->succeeded())->toBeTrue();
    expect($second->succeeded())->toBeTrue();
    expect($second->lines())->toBe(['SKIP app/Models/User.php $fillable unchanged']);
});

it('writes nothing on a dry run', function () {
    $before = Console::read('app/Models/User.php');
    $result = Console::run('archetype:fillable app/Models/User.php nickname --add --dry-run');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('DRY app/Models/User.php $fillable added to');
    expect($result->output)->toContain("+         'nickname',");
    expect(Console::read('app/Models/User.php'))->toBe($before);
});

it('exits non-zero when there is nothing it could act on', function () {
    Console::write('app/helpers.php', "<?php\n\nfunction thing()\n{\n    return 1;\n}\n");

    // There is no class here to rename, so this must not report OK.
    $result = Console::run('archetype:className app/helpers.php Thing');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('only works on classes, and this is a file');
    expect(Console::read('app/helpers.php'))->toContain('function thing()');
});

it('refuses a change the construct cannot take, before writing any of it', function () {
    Console::write('app/Enums/Status.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum Status: string
        {
            case Active = 'active';
        }
        PHP);

    $result = Console::run('archetype:property app/Enums/Status.php table users');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('archetype:property only works on classes, and this is an enum');
    expect(Console::read('app/Enums/Status.php'))->not->toContain('table');
});

it('applies one change across a whole directory', function () {
    Console::write('app/Models/Project.php', modelSource('Project'));
    Console::write('app/Models/Task.php', modelSource('Task'));

    $result = Console::run('archetype:useTrait', [
        'target' => 'app/Models',
        'names' => ['Illuminate\Database\Eloquent\SoftDeletes'],
        '--add' => true,
    ]);

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toContain('3 changed, 0 unchanged, 0 failed of 3 files');
    expect(Console::read('app/Models/Project.php'))->toContain('use SoftDeletes;');
    expect(Console::read('app/Models/Task.php'))->toContain('use SoftDeletes;');
});

it('narrows a directory change with a filter', function () {
    Console::write('app/Models/Project.php', modelSource('Project'));

    $result = Console::run('archetype:fillable app/Models slug --add --extends=Model');

    expect($result->succeeded())->toBeTrue();
    expect(Console::read('app/Models/Project.php'))->toContain("'slug'");
    expect(Console::read('app/Models/User.php'))->not->toContain("'slug'");
});

it('refuses a filter when the target is a single file', function () {
    $result = Console::run('archetype:fillable app/Models/User.php slug --add --extends=Model');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('--extends only applies when the target is a directory');
});

it('reports a mutation as json', function () {
    $payload = Console::run('archetype:fillable app/Models/User.php nickname --add --json')->json();

    expect($payload['ok'])->toBeTrue();
    expect($payload['changed'])->toBe(1);
    expect($payload['dryRun'])->toBeFalse();
    expect($payload['results'][0]['status'])->toBe('changed');
    expect($payload['results'][0]['diff'])->toContain('nickname');
});

it('fails on a target that does not exist', function () {
    $result = Console::run('archetype:fillable app/Models/Nope.php slug --add');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toStartWith('ERR app/Models/Nope.php');
});

function modelSource(string $name): string
{
    return <<<PHP
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class $name extends Model
        {
            protected \$fillable = [
                'name',
            ];
        }
        PHP;
}

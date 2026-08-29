<?php

use Archetype\Tests\Support\Console;

it('writes to the $casts property when the model uses one', function () {
    $result = Console::run('archetype:set-casts app/Models/User.php is_admin=boolean password=hashed');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Models/User.php casts +2 ~0 in $casts');
    expect(Console::read('app/Models/User.php'))
        ->toContain("'email_verified_at' => 'datetime',")
        ->toContain("'is_admin' => 'boolean',")
        ->toContain("'password' => 'hashed',");
});

it('writes to the casts() method when the model has one', function () {
    Console::write('app/Models/Project.php', <<<'PHP'
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class Project extends Model
        {
            protected function casts(): array
            {
                return [
                    'published_at' => 'datetime',
                ];
            }
        }
        PHP);

    $result = Console::run('archetype:set-casts app/Models/Project.php archived=boolean');

    expect($result->lines()[0])->toBe('OK app/Models/Project.php casts +1 ~0 in casts()');

    $source = Console::read('app/Models/Project.php');

    expect($source)->toContain("'archived' => 'boolean',");
    expect($source)->not->toContain('protected $casts');
});

it('takes an expression as the cast', function () {
    Console::run('archetype:set-casts app/Models/User.php status=Status::class role=\'AsEnum:role\'');

    expect(Console::read('app/Models/User.php'))
        ->toContain("'status' => Status::class,")
        ->toContain("'role' => 'AsEnum:role',");
});

it('creates the property when the model casts nothing yet', function () {
    Console::write('app/Models/Task.php', <<<'PHP'
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class Task extends Model
        {
        }
        PHP);

    Console::run('archetype:set-casts app/Models/Task.php done=boolean');

    expect(Console::read('app/Models/Task.php'))->toContain("protected \$casts = [\n        'done' => 'boolean',\n    ];");
});

it('updates a cast rather than duplicating it', function () {
    $result = Console::run('archetype:set-casts app/Models/User.php email_verified_at=immutable_datetime');

    expect($result->lines()[0])->toBe('OK app/Models/User.php casts +0 ~1 in $casts');
    expect(Console::read('app/Models/User.php'))
        ->toContain("'email_verified_at' => 'immutable_datetime',")
        ->not->toContain("'email_verified_at' => 'datetime',");
});

it('skips casts already set', function () {
    $result = Console::run('archetype:set-casts app/Models/User.php email_verified_at=datetime');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/User.php casts unchanged']);
});

it('rejects a pair that is not one', function () {
    $result = Console::run('archetype:set-casts app/Models/User.php nonsense');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain("expected field=cast, got 'nonsense'");
});

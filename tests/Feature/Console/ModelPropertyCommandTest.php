<?php

use Archetype\Console\Commands\ModelPropertyCommand;
use Archetype\Tests\Support\Console;
use Illuminate\Support\Facades\Artisan;

it('gives every LaravelFile model property its own command', function () {
    $registered = array_keys(Artisan::all());

    foreach (array_keys(ModelPropertyCommand::PROPERTIES) as $property) {
        expect($registered)->toContain("archetype:$property");
    }
});

it('reads fillable', function () {
    expect(Console::run('archetype:fillable app/Models/User.php')->output)
        ->toBe('["name","email","password"]');
});

it('adds to fillable', function () {
    $result = Console::run('archetype:fillable app/Models/User.php nickname --add');

    expect($result->lines()[0])->toBe('OK app/Models/User.php $fillable added to');
    expect(Console::read('app/Models/User.php'))->toContain("'nickname',");
});

it('sets fillable wholesale without --add, as the endpoint does', function () {
    Console::run('archetype:fillable app/Models/User.php \'["only_this"]\'');

    $source = Console::read('app/Models/User.php');

    expect($source)->toContain("'only_this',");
    // 'password' also lives in $hidden, so 'email' is the one that proves it.
    expect($source)->not->toContain("'email',");
});

it('sets the table', function () {
    Console::run('archetype:table app/Models/User.php gdpr_users');

    expect(Console::read('app/Models/User.php'))->toContain("protected \$table = 'gdpr_users';");
});

it('writes casts into the $casts property', function () {
    Console::run('archetype:casts app/Models/User.php \'{"is_admin":"boolean"}\' --add');

    expect(Console::read('app/Models/User.php'))
        ->toContain("'email_verified_at' => 'datetime',")
        ->toContain("'is_admin' => 'boolean',");
});

it('refuses to write $casts on a model that declares a casts() method', function () {
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

    $result = Console::run('archetype:casts app/Models/Project.php \'{"archived":"boolean"}\' --add');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('two casting mechanisms');
    expect($result->output)->toContain('archetype:set-array-key');
    expect(Console::read('app/Models/Project.php'))->not->toContain('protected $casts');
});

it('empties and removes', function () {
    Console::run('archetype:hidden app/Models/User.php --empty');
    expect(Console::read('app/Models/User.php'))->toContain('protected $hidden = [];');

    Console::run('archetype:hidden app/Models/User.php --remove');
    expect(Console::read('app/Models/User.php'))->not->toContain('$hidden');
});

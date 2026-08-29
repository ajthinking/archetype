<?php

use Archetype\Tests\Support\Console;

it('reads the import statements', function () {
    expect(Console::run('archetype:use app/Models/User.php')->output)
        ->toContain('Illuminate\\\\Notifications\\\\Notifiable');
});

it('adds imports with --add', function () {
    $result = Console::run('archetype:use', [
        'target' => 'app/Models/User.php',
        'names' => ['App\Contracts\Auditable', 'Illuminate\Support\Str'],
        '--add' => true,
    ]);

    expect($result->lines()[0])->toBe('OK app/Models/User.php import +2');
    expect(Console::read('app/Models/User.php'))
        ->toContain('use App\Contracts\Auditable;')
        ->toContain('use Illuminate\Support\Str;');
});

it('replaces the imports without --add, as the endpoint does', function () {
    Console::run('archetype:use', [
        'target' => 'app/Models/User.php',
        'names' => ['App\Contracts\Auditable'],
    ]);

    $source = Console::read('app/Models/User.php');

    expect($source)->toContain('use App\Contracts\Auditable;');
    // The trait use line also says Notifiable, so name the import exactly.
    expect($source)->not->toContain('use Illuminate\Notifications\Notifiable;');
});

it('skips imports already there', function () {
    $result = Console::run('archetype:use', [
        'target' => 'app/Models/User.php',
        'names' => ['Illuminate\Notifications\Notifiable'],
        '--add' => true,
    ]);

    expect($result->lines())->toBe(['SKIP app/Models/User.php imports unchanged']);
});

it('reads the traits a class uses', function () {
    expect(Console::run('archetype:useTrait app/Models/User.php')->output)
        ->toBe('["HasApiTokens","HasFactory","Notifiable"]');
});

it('uses a trait and imports it in one step', function () {
    Console::run('archetype:useTrait', [
        'target' => 'app/Models/User.php',
        'names' => ['Illuminate\Database\Eloquent\SoftDeletes'],
        '--add' => true,
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('use Illuminate\Database\Eloquent\SoftDeletes;')
        ->toContain('use SoftDeletes;');
});

it('leaves the import alone when told to', function () {
    Console::run('archetype:useTrait', [
        'target' => 'app/Models/User.php',
        'names' => ['Illuminate\Database\Eloquent\SoftDeletes'],
        '--add' => true,
        '--no-import' => true,
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('use SoftDeletes;')
        ->not->toContain('use Illuminate\Database\Eloquent\SoftDeletes;');
});

it('reads and adds interfaces', function () {
    expect(Console::run('archetype:implements app/Models/User.php')->output)->toBe('[]');

    Console::run('archetype:implements', [
        'target' => 'app/Models/User.php',
        'names' => ['Illuminate\Contracts\Auth\MustVerifyEmail'],
        '--add' => true,
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('class User extends Authenticatable implements MustVerifyEmail');
});

it('reads and sets the parent class', function () {
    expect(Console::run('archetype:extends app/Models/User.php')->output)->toBe('Authenticatable');

    Console::run('archetype:extends', [
        'target' => 'app/Models/User.php',
        'name' => 'Illuminate\Database\Eloquent\Model',
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('use Illuminate\Database\Eloquent\Model;')
        ->toContain('class User extends Model');
});

it('skips a parent class already set', function () {
    expect(Console::run('archetype:extends app/Models/User.php Authenticatable')->lines())
        ->toBe(['SKIP app/Models/User.php extends unchanged']);
});

it('reads, sets and removes the namespace', function () {
    expect(Console::run('archetype:namespace app/Models/User.php')->output)->toBe('App\Models');

    Console::run('archetype:namespace', [
        'target' => 'app/Models/User.php',
        'value' => 'App\Domain\Models',
    ]);

    expect(Console::read('app/Models/User.php'))->toContain('namespace App\Domain\Models;');

    Console::run('archetype:namespace app/Models/User.php --remove');

    expect(Console::read('app/Models/User.php'))->not->toContain('namespace');
});

it('reads and sets the class name', function () {
    expect(Console::run('archetype:className app/Models/User.php')->output)->toBe('User');

    Console::run('archetype:className app/Models/User.php Account');

    expect(Console::read('app/Models/User.php'))->toContain('class Account extends Authenticatable');
});

it('answers with the full class name when asked', function () {
    expect(Console::run('archetype:className app/Models/User.php --full')->output)
        ->toBe('App\Models\User');
});

it('lists the method names', function () {
    Console::write('app/Models/Project.php', <<<'PHP'
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class Project extends Model
        {
            public function tasks()
            {
                return $this->hasMany(Task::class);
            }

            public function isActive()
            {
                return true;
            }
        }
        PHP);

    expect(Console::run('archetype:methodNames app/Models/Project.php')->output)
        ->toBe('["tasks","isActive"]');
});

it('reads, sets and removes a class constant', function () {
    Console::run('archetype:classConstant app/Models/User.php HOME /dashboard');

    expect(Console::read('app/Models/User.php'))->toContain("const HOME = '/dashboard';");
    expect(Console::run('archetype:classConstant app/Models/User.php HOME')->output)->toBe('/dashboard');

    Console::run('archetype:classConstant app/Models/User.php HOME --remove');

    expect(Console::read('app/Models/User.php'))->not->toContain('HOME');
});

it('skips a constant already set to that value', function () {
    Console::run('archetype:classConstant app/Models/User.php HOME /dashboard');

    expect(Console::run('archetype:classConstant app/Models/User.php HOME /dashboard')->lines())
        ->toBe(['SKIP app/Models/User.php HOME unchanged']);
});

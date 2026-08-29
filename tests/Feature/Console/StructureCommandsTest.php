<?php

use Archetype\Tests\Support\Console;

it('adds imports', function () {
    $result = Console::run('archetype:add-use', [
        'target' => 'app/Models/User.php',
        'imports' => ['App\Contracts\Auditable', 'Illuminate\Support\Str'],
    ]);

    expect($result->lines()[0])->toBe('OK app/Models/User.php import +2');
    expect(Console::read('app/Models/User.php'))
        ->toContain('use App\Contracts\Auditable;')
        ->toContain('use Illuminate\Support\Str;');
});

it('skips imports already there', function () {
    $result = Console::run('archetype:add-use', [
        'target' => 'app/Models/User.php',
        'imports' => ['Illuminate\Notifications\Notifiable'],
    ]);

    expect($result->lines())->toBe(['SKIP app/Models/User.php imports unchanged']);
});

it('removes imports', function () {
    Console::run('archetype:remove-use', [
        'target' => 'app/Models/User.php',
        'imports' => ['Illuminate\Contracts\Auth\MustVerifyEmail'],
    ]);

    expect(Console::read('app/Models/User.php'))->not->toContain('MustVerifyEmail');
});

it('uses a trait and imports it in one step', function () {
    Console::run('archetype:add-trait', [
        'target' => 'app/Models/User.php',
        'traits' => ['Illuminate\Database\Eloquent\SoftDeletes'],
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('use Illuminate\Database\Eloquent\SoftDeletes;')
        ->toContain('use SoftDeletes;');
});

it('implements an interface and imports it in one step', function () {
    Console::run('archetype:add-implements', [
        'target' => 'app/Models/User.php',
        'interfaces' => ['Illuminate\Contracts\Auth\MustVerifyEmail'],
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('class User extends Authenticatable implements MustVerifyEmail');
});

it('sets the parent class', function () {
    Console::run('archetype:set-extends', [
        'target' => 'app/Models/User.php',
        'parent' => 'Illuminate\Database\Eloquent\Model',
    ]);

    expect(Console::read('app/Models/User.php'))
        ->toContain('use Illuminate\Database\Eloquent\Model;')
        ->toContain('class User extends Model');
});

it('skips a parent class already set', function () {
    $result = Console::run('archetype:set-extends app/Models/User.php Authenticatable');

    expect($result->lines())->toBe(['SKIP app/Models/User.php extends unchanged']);
});

it('sets the namespace', function () {
    Console::run('archetype:set-namespace', [
        'target' => 'app/Models/User.php',
        'namespace' => 'App\Domain\Models',
    ]);

    expect(Console::read('app/Models/User.php'))->toContain('namespace App\Domain\Models;');
});

it('renames the class', function () {
    Console::run('archetype:rename-class app/Models/User.php Account');

    expect(Console::read('app/Models/User.php'))->toContain('class Account extends Authenticatable');
});

it('refuses to rename an enum', function () {
    Console::write('app/Enums/Status.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum Status: string
        {
            case Active = 'active';
        }
        PHP);

    $result = Console::run('archetype:rename-class app/Enums/Status.php ProjectStatus');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('only works on classes, and this is an enum');
    expect(Console::read('app/Enums/Status.php'))->toContain('enum Status: string');
});

it('sets and removes a class constant', function () {
    Console::run('archetype:set-const app/Models/User.php HOME /dashboard');

    expect(Console::read('app/Models/User.php'))->toContain("const HOME = '/dashboard';");

    Console::run('archetype:remove-const app/Models/User.php HOME');

    expect(Console::read('app/Models/User.php'))->not->toContain('HOME');
});

it('refuses to set a constant on an interface', function () {
    Console::write('app/Contracts/Payable.php', <<<'PHP'
        <?php

        namespace App\Contracts;

        interface Payable
        {
            public function pay(): void;
        }
        PHP);

    $result = Console::run('archetype:set-const app/Contracts/Payable.php CURRENCY EUR');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('only works on classes, and this is an interface');
    expect(Console::read('app/Contracts/Payable.php'))->not->toContain('CURRENCY');
});

it('skips a constant already set', function () {
    Console::run('archetype:set-const app/Models/User.php HOME /dashboard');
    $again = Console::run('archetype:set-const app/Models/User.php HOME /dashboard');

    expect($again->lines())->toBe(['SKIP app/Models/User.php HOME unchanged']);
});

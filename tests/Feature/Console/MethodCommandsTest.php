<?php

use Archetype\Tests\Support\Console;

beforeEach(function () {
    Console::write('app/Models/Project.php', <<<'PHP'
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class Project extends Model
        {
            public function isActive()
            {
                return true;
            }
        }
        PHP);
});

it('adds a method', function () {
    $result = Console::run('archetype:add-method app/Models/Project.php --code="public function scopeActive(\$query) { return \$query->where(\'active\', true); }"');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Models/Project.php fn scopeActive added');
    expect(Console::read('app/Models/Project.php'))
        ->toContain('public function scopeActive($query)')
        ->toContain("return \$query->where('active', true);");
});

it('adds the method after the ones already there', function () {
    Console::run('archetype:add-method app/Models/Project.php --code="public function scopeActive(\$query) { return \$query; }"');

    $source = Console::read('app/Models/Project.php');

    expect(strpos($source, 'scopeActive'))->toBeGreaterThan(strpos($source, 'isActive'));
});

it('will not add a method that is already there', function () {
    $result = Console::run('archetype:add-method app/Models/Project.php --code="public function isActive() { return false; }"');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/Project.php isActive exists']);
});

it('replaces a method', function () {
    $result = Console::run('archetype:replace-method app/Models/Project.php isActive --code="public function isActive(): bool { return \$this->active; }"');

    expect($result->succeeded())->toBeTrue();
    expect(Console::read('app/Models/Project.php'))
        ->toContain('public function isActive() : bool')
        ->not->toContain('return true;');
});

it('removes a method', function () {
    Console::run('archetype:remove-method app/Models/Project.php isActive');

    expect(Console::read('app/Models/Project.php'))->not->toContain('isActive');
});

it('reports a method that was never there rather than failing', function () {
    $result = Console::run('archetype:remove-method app/Models/Project.php missing');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Models/Project.php no fn missing']);
});

it('insists on code for the operations that need it', function () {
    expect(Console::run('archetype:add-method app/Models/Project.php')->output)->toContain('--code is required');
    expect(Console::run('archetype:replace-method app/Models/Project.php isActive')->output)->toContain('--code is required');
});

it('rejects code that is not a method', function () {
    $result = Console::run('archetype:add-method app/Models/Project.php --code="\$x = 1;"');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('could not parse the given code');
});

it('adds a method to an enum', function () {
    Console::write('app/Enums/Status.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum Status: string
        {
            case Active = 'active';
        }
        PHP);

    $result = Console::run('archetype:add-method app/Enums/Status.php --code="public function label(): string { return ucfirst(\$this->value); }"');

    expect($result->succeeded())->toBeTrue();
    expect(Console::read('app/Enums/Status.php'))->toContain('public function label() : string');
});

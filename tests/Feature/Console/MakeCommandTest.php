<?php

use Archetype\Tests\Support\Console;

it('creates a class from a class name', function () {
    $result = Console::run('archetype:make', ['name' => 'App\Services\Billing']);

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Services/Billing.php created');
    expect(Console::read('app/Services/Billing.php'))
        ->toContain('namespace App\Services;')
        ->toContain('class Billing');
});

it('creates a class from a path', function () {
    Console::run('archetype:make app/Services/Billing.php');

    expect(Console::read('app/Services/Billing.php'))->toContain('class Billing');
});

it('creates a class with a parent, interfaces and traits', function () {
    Console::run('archetype:make', [
        'name' => 'App\Models\Invoice',
        '--extends' => 'Illuminate\Database\Eloquent\Model',
        '--implements' => ['App\Contracts\Payable'],
        '--trait' => ['Illuminate\Database\Eloquent\Factories\HasFactory'],
    ]);

    expect(Console::read('app/Models/Invoice.php'))
        ->toContain('use Illuminate\Database\Eloquent\Model;')
        ->toContain('use App\Contracts\Payable;')
        ->toContain('class Invoice extends Model implements Payable')
        ->toContain('use HasFactory;');
});

it('creates an empty file', function () {
    Console::run('archetype:make app/helpers.php --file');

    expect(Console::read('app/helpers.php'))->toContain('<?php');
});

it('refuses to overwrite unless told to', function () {
    $result = Console::run('archetype:make app/Models/User.php');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('already exists — pass --force');
});

it('overwrites when forced', function () {
    $result = Console::run('archetype:make app/Models/User.php --force');

    expect($result->succeeded())->toBeTrue();
    expect(Console::read('app/Models/User.php'))->not->toContain('$fillable');
});

it('returns the created source as json', function () {
    $payload = Console::run('archetype:make', ['name' => 'App\Services\Billing', '--json' => true])->json();

    expect($payload['ok'])->toBeTrue();
    expect($payload['file'])->toBe('app/Services/Billing.php');
    expect($payload['source'])->toContain('class Billing');
});

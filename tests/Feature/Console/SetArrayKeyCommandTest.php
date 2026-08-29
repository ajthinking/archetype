<?php

use Archetype\Tests\Support\Console;

beforeEach(function () {
    Console::write('app/Http/Requests/StoreTaskRequest.php', <<<'PHP'
        <?php

        namespace App\Http\Requests;

        use Illuminate\Foundation\Http\FormRequest;

        class StoreTaskRequest extends FormRequest
        {
            public function rules(): array
            {
                return [
                    'title' => 'required|string|max:255',
                ];
            }
        }
        PHP);
});

it('adds a key to the array a method returns', function () {
    $result = Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules due_at \'nullable|date\'');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Http/Requests/StoreTaskRequest.php rules()[due_at] added');
    expect(Console::read('app/Http/Requests/StoreTaskRequest.php'))
        ->toContain("'due_at' => 'nullable|date',")
        ->toContain("'title' => 'required|string|max:255',");
});

it('takes any php expression as the value', function () {
    Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules tags "[\'array\', \'max:5\']"');

    expect(Console::read('app/Http/Requests/StoreTaskRequest.php'))
        ->toContain("'tags' => [\n                'array',\n                'max:5',\n            ],");
});

it('updates a key that is already there', function () {
    $result = Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules title required');

    expect($result->lines()[0])->toContain('rules()[title] updated');
    expect(Console::read('app/Http/Requests/StoreTaskRequest.php'))
        ->toContain("'title' => 'required',")
        ->not->toContain('max:255');
});

it('skips a key already set to that value', function () {
    $result = Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules title \'required|string|max:255\'');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Http/Requests/StoreTaskRequest.php rules()[title] unchanged']);
});

it('removes a key', function () {
    Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules title --remove');

    expect(Console::read('app/Http/Requests/StoreTaskRequest.php'))->not->toContain("'title'");
});

it('appends a value with no key', function () {
    Console::write('app/Providers/Listener.php', <<<'PHP'
        <?php

        namespace App\Providers;

        class Listener
        {
            public function subscribe(): array
            {
                return [
                    'first',
                ];
            }
        }
        PHP);

    Console::run('archetype:set-array-key app/Providers/Listener.php subscribe ignored \'second\' --append');

    expect(Console::read('app/Providers/Listener.php'))->toContain("'second',");
});

it('insists on a value unless it is removing', function () {
    $result = Console::run('archetype:set-array-key app/Http/Requests/StoreTaskRequest.php rules title');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('a value is required unless --remove is given');
});

it('fails when the method does not return an array literal', function () {
    $result = Console::run('archetype:set-array-key app/Models/User.php getTable name x');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('getTable() does not return an array literal');
});

it('reaches the array a laravel 11 casts method returns', function () {
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

    Console::run('archetype:set-array-key app/Models/Project.php casts archived boolean');

    expect(Console::read('app/Models/Project.php'))->toContain("'archived' => 'boolean',");
});

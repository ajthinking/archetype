<?php

use Archetype\Tests\Support\Console;

beforeEach(function () {
    Console::write('app/Http/Requests/StoreTaskRequest.php', <<<'PHP'
        <?php

        namespace App\Http\Requests;

        use Illuminate\Foundation\Http\FormRequest;

        class StoreTaskRequest extends FormRequest
        {
            /**
             * The validation rules.
             */
            public function rules(): array
            {
                return [
                    'title' => 'required|string|max:255',
                ];
            }
        }
        PHP);
});

it('prints a method exactly as written, doc block included', function () {
    $result = Console::run('archetype:show app/Http/Requests/StoreTaskRequest.php rules');

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toContain('app/Http/Requests/StoreTaskRequest.php::rules');
    expect($result->output)->toContain('     * The validation rules.');
    expect($result->output)->toContain("            'title' => 'required|string|max:255',");
});

it('returns the source under a json key', function () {
    $payload = Console::run('archetype:show app/Http/Requests/StoreTaskRequest.php rules --json')->json();

    expect($payload['method'])->toBe('rules');
    expect($payload['source'])->toContain('public function rules(): array');
});

it('fails when the method is not there', function () {
    $result = Console::run('archetype:show app/Http/Requests/StoreTaskRequest.php missing');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain("no method 'missing'");
});

it('finds the method across a directory', function () {
    $result = Console::run('archetype:show app/Http/Requests rules');

    expect($result->succeeded())->toBeTrue();
    expect($result->output)->toContain('StoreTaskRequest.php::rules');
});

<?php

use Archetype\Tests\Support\Console;

it('summarises a model', function () {
    $result = Console::run('archetype:inspect app/Models/User.php');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toContain('app/Models/User.php');
    expect($result->lines())->toContain('class App\Models\User extends Authenticatable');
    expect($result->lines())->toContain('uses HasApiTokens, HasFactory, Notifiable');
    expect($result->lines())->toContain('prop protected $fillable = ["name","email","password"]');
    expect($result->lines())->toContain('prop protected $casts = {"email_verified_at":"datetime"}');
});

it('accepts a class name as the target', function () {
    expect(Console::run('archetype:inspect', ['target' => 'App\Models\User'])->lines())
        ->toContain('class App\Models\User extends Authenticatable');
});

it('limits the summary to the sections asked for', function () {
    $lines = Console::run('archetype:inspect app/Models/User.php props')->lines();

    expect($lines)->toHaveCount(4);
    expect(implode("\n", $lines))->not->toContain('class App\Models\User');
});

it('rejects a section it does not have', function () {
    $result = Console::run('archetype:inspect app/Models/User.php nonsense');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain("unknown section 'nonsense'");
});

it('describes an enum as an enum, with its cases', function () {
    Console::write('app/Enums/Status.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum Status: string
        {
            case Active = 'active';
            case Archived = 'archived';

            public function label(): string
            {
                return ucfirst($this->value);
            }
        }
        PHP);

    $lines = Console::run('archetype:inspect app/Enums/Status.php')->lines();

    expect($lines)->toContain('enum App\Enums\Status');
    expect($lines)->toContain('case Active = "active"');
    expect($lines)->toContain('case Archived = "archived"');
    expect($lines)->toContain('fn public label(): string [4 lines]');
});

it('reports relationships it can read from method bodies', function () {
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

            public function owner()
            {
                return $this->belongsTo(User::class, 'owner_id');
            }
        }
        PHP);

    $lines = Console::run('archetype:inspect app/Models/Project.php relations')->lines();

    expect($lines)->toContain('rel tasks hasMany Task');
    expect($lines)->toContain('rel owner belongsTo User');
});

it('summarises every class in a directory', function () {
    $payload = Console::run('archetype:inspect app/Models meta --json')->json();

    expect($payload['count'])->toBe(1);
    expect($payload['files'][0]['name'])->toBe('User');
});

it('says a value is unknown rather than guessing it', function () {
    Console::write('app/Odd.php', <<<'PHP'
        <?php

        namespace App;

        class Odd
        {
            protected $computed = SOME_UNDEFINED_CONSTANT;
        }
        PHP);

    expect(Console::run('archetype:inspect app/Odd.php props')->lines())
        ->toContain('prop protected $computed = ?');
});

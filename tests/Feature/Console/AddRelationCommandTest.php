<?php

use Archetype\Tests\Support\Console;

beforeEach(function () {
    Console::write('app/Models/Project.php', <<<'PHP'
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class Project extends Model
        {
            protected $fillable = [
                'name',
            ];
        }
        PHP);
});

it('adds a hasMany with the conventional name', function () {
    $result = Console::run('archetype:add-relation app/Models/Project.php hasMany Task');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Models/Project.php hasMany tasks');
    expect(Console::read('app/Models/Project.php'))
        ->toContain('return $this->hasMany(Task::class);')
        ->toContain('Get the associated Tasks');
});

it('appends the method after what is already there', function () {
    Console::run('archetype:add-relation app/Models/Project.php hasMany Task');

    $source = Console::read('app/Models/Project.php');

    expect(strpos($source, 'public function tasks'))->toBeGreaterThan(strpos($source, '$fillable'));
});

it('imports a related class from another namespace', function () {
    Console::run('archetype:add-relation', [
        'target' => 'app/Models/Project.php',
        'type' => 'belongsTo',
        'related' => 'App\Domain\Owner',
    ]);

    expect(Console::read('app/Models/Project.php'))
        ->toContain('use App\Domain\Owner;')
        ->toContain('return $this->belongsTo(Owner::class);');
});

it('overrides the method name', function () {
    Console::run('archetype:add-relation app/Models/Project.php belongsTo User --name=owner --foreign-key=owner_id');

    expect(Console::read('app/Models/Project.php'))
        ->toContain('public function owner()')
        ->toContain("return \$this->belongsTo(User::class, 'owner_id');");
});

it('writes a belongsToMany with a pivot', function () {
    Console::run('archetype:add-relation app/Models/Project.php belongsToMany Label --table=label_project --with-pivot=sort,note --with-timestamps');

    expect(Console::read('app/Models/Project.php'))->toContain(
        "return \$this->belongsToMany(Label::class, 'label_project')->withPivot('sort', 'note')->withTimestamps();"
    );
});

it('writes the polymorphic relations', function () {
    Console::run('archetype:add-relation app/Models/Project.php morphMany Comment --morph-name=commentable');

    expect(Console::read('app/Models/Project.php'))
        ->toContain("return \$this->morphMany(Comment::class, 'commentable');")
        ->toContain('public function comments()');
});

it('writes a through relation', function () {
    Console::run('archetype:add-relation app/Models/Project.php hasManyThrough Comment --through=Task');

    expect(Console::read('app/Models/Project.php'))
        ->toContain('return $this->hasManyThrough(Comment::class, Task::class);');
});

it('will not add a relation that is already there', function () {
    Console::run('archetype:add-relation app/Models/Project.php hasMany Task');
    $again = Console::run('archetype:add-relation app/Models/Project.php hasMany Task');

    expect($again->succeeded())->toBeTrue();
    expect($again->lines())->toBe(['SKIP app/Models/Project.php tasks exists']);
});

it('rejects a relation type it does not have', function () {
    $result = Console::run('archetype:add-relation app/Models/Project.php hasSome Task');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain("unknown relation type 'hasSome'");
});

it('insists on the arguments a relation needs', function () {
    expect(Console::run('archetype:add-relation app/Models/Project.php morphMany Comment')->output)
        ->toContain('needs --morph-name');

    expect(Console::run('archetype:add-relation app/Models/Project.php hasManyThrough Comment')->output)
        ->toContain('needs --through');

    expect(Console::run('archetype:add-relation app/Models/Project.php hasMany')->output)
        ->toContain('needs a related class');
});

it('refuses to guess an argument the caller skipped', function () {
    $result = Console::run('archetype:add-relation app/Models/Project.php hasMany Task --local-key=uuid');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('--local-key cannot be given without the arguments before it');
});

<?php

use Archetype\Facades\LaravelFile;
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

it('gives each relationship endpoint its own command', function () {
    $registered = array_keys(Illuminate\Support\Facades\Artisan::all());

    foreach (['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'] as $type) {
        expect($registered)->toContain("archetype:$type");
    }
});

it('produces exactly what the endpoint produces', function () {
    Console::run('archetype:hasMany app/Models/Project.php Task');
    $viaConsole = Console::read('app/Models/Project.php');

    // Reset, then do the same thing straight through the PHP API.
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

    LaravelFile::load('app/Models/Project.php')->hasMany('Task')->save();

    expect($viaConsole)->toBe(Console::read('app/Models/Project.php'));
});

it('names the method the way the endpoint does', function () {
    $result = Console::run('archetype:hasMany app/Models/Project.php Task');

    expect($result->lines()[0])->toBe('OK app/Models/Project.php hasMany tasks');
    expect(Console::read('app/Models/Project.php'))
        ->toContain('return $this->hasMany(Task::class);');
});

it('imports a related class from another namespace', function () {
    Console::run('archetype:belongsTo', [
        'target' => 'app/Models/Project.php',
        'related' => 'App\Domain\Owner',
    ]);

    expect(Console::read('app/Models/Project.php'))
        ->toContain('use App\Domain\Owner;')
        ->toContain('return $this->belongsTo(Owner::class);');
});

it('takes the arguments the endpoint cannot express', function () {
    Console::run('archetype:belongsToMany app/Models/Project.php Label --table=label_project --with-pivot=sort,note --with-timestamps');

    expect(Console::read('app/Models/Project.php'))->toContain(
        "return \$this->belongsToMany(Label::class, 'label_project')->withPivot('sort', 'note')->withTimestamps();"
    );
});

it('overrides the method name', function () {
    Console::run('archetype:belongsTo app/Models/Project.php User --name=owner --foreign-key=owner_id');

    expect(Console::read('app/Models/Project.php'))
        ->toContain('public function owner()')
        ->toContain("return \$this->belongsTo(User::class, 'owner_id');");
});

it('offers the relation types the endpoints do not have', function () {
    Console::run('archetype:morphMany app/Models/Project.php Comment --morph-name=commentable');
    // morphMany already claimed `comments`, so name this one.
    Console::run('archetype:hasManyThrough app/Models/Project.php Comment --through=Task --name=taskComments');

    expect(Console::read('app/Models/Project.php'))
        ->toContain("return \$this->morphMany(Comment::class, 'commentable');")
        ->toContain('return $this->hasManyThrough(Comment::class, Task::class);');
});

it('will not add a relation that is already there', function () {
    Console::run('archetype:hasMany app/Models/Project.php Task');
    $again = Console::run('archetype:hasMany app/Models/Project.php Task');

    expect($again->succeeded())->toBeTrue();
    expect($again->lines())->toBe(['SKIP app/Models/Project.php tasks exists']);
});

it('insists on the arguments a relation needs', function () {
    expect(Console::run('archetype:morphMany app/Models/Project.php Comment')->output)
        ->toContain('needs --morph-name');

    expect(Console::run('archetype:hasManyThrough app/Models/Project.php Comment')->output)
        ->toContain('needs --through');

    expect(Console::run('archetype:hasMany app/Models/Project.php')->output)
        ->toContain('needs a related class');
});

it('refuses to guess an argument the caller skipped', function () {
    $result = Console::run('archetype:hasMany app/Models/Project.php Task --local-key=uuid');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('--local-key cannot be given without the arguments before it');
});

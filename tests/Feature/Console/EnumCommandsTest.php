<?php

use Archetype\Tests\Support\Console;

beforeEach(function () {
    Console::write('app/Enums/ProjectStatus.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum ProjectStatus: string
        {
            case Draft = 'draft';
            case Active = 'active';

            public function label(): string
            {
                return ucfirst($this->value);
            }
        }
        PHP);
});

it('adds a case after the ones already there', function () {
    $result = Console::run('archetype:add-case app/Enums/ProjectStatus.php OnHold on_hold');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines()[0])->toBe('OK app/Enums/ProjectStatus.php case OnHold');

    $source = Console::read('app/Enums/ProjectStatus.php');

    expect($source)->toContain("case OnHold = 'on_hold';");
    expect(strpos($source, 'OnHold'))->toBeGreaterThan(strpos($source, 'Active'));
    expect(strpos($source, 'OnHold'))->toBeLessThan(strpos($source, 'function label'));
});

it('adds a case with an integer value', function () {
    Console::run('archetype:add-case app/Enums/ProjectStatus.php Closed 3');

    expect(Console::read('app/Enums/ProjectStatus.php'))->toContain('case Closed = 3;');
});

it('adds a case with no backing value', function () {
    Console::write('app/Enums/Suit.php', <<<'PHP'
        <?php

        namespace App\Enums;

        enum Suit
        {
            case Hearts;
        }
        PHP);

    Console::run('archetype:add-case app/Enums/Suit.php Spades');

    expect(Console::read('app/Enums/Suit.php'))->toContain('case Spades;');
});

it('will not add a case that is already there', function () {
    $result = Console::run('archetype:add-case app/Enums/ProjectStatus.php Draft draft');

    expect($result->succeeded())->toBeTrue();
    expect($result->lines())->toBe(['SKIP app/Enums/ProjectStatus.php case Draft exists']);
});

it('refuses to add a case to something that is not an enum', function () {
    $result = Console::run('archetype:add-case app/Models/User.php Draft draft');

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('not an enum, it is a class');
});

it('refuses to add an interface to an enum, and writes nothing at all', function () {
    // The implements endpoint addresses classes, so this cannot be done here.
    // What matters is that it is refused before the import is written: half a
    // change that reports success is worse than no change at all.
    $result = Console::run('archetype:add-implements', [
        'target' => 'app/Enums/ProjectStatus.php',
        'interfaces' => ['App\Contracts\HasColor'],
    ]);

    expect($result->succeeded())->toBeFalse();
    expect($result->output)->toContain('archetype:add-implements only works on classes, and this is an enum');
    expect(Console::read('app/Enums/ProjectStatus.php'))->not->toContain('HasColor');
});

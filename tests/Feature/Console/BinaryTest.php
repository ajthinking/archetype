<?php

use Illuminate\Support\Facades\File;

/**
 * The `archetype` binary itself, against a stub `artisan` that reports the
 * arguments it was handed. What is under test is the mapping from the command
 * line a caller types to the Artisan command that runs.
 */
function stubApplication(): string
{
    $root = sys_get_temp_dir().'/archetype-bin-'.bin2hex(random_bytes(4));

    File::ensureDirectoryExists($root.'/app/Models');
    File::put($root.'/artisan', "<?php\n\nfwrite(STDOUT, implode(' ', array_slice(\$argv, 1)).PHP_EOL);\n");

    return $root;
}

function runBinary(string $cwd, string $arguments = ''): array
{
    $binary = escapeshellarg(dirname(__DIR__, 3).'/bin/archetype');

    exec("cd ".escapeshellarg($cwd)." && ".PHP_BINARY." $binary $arguments 2>&1", $output, $status);

    return [$status, implode("\n", $output)];
}

afterEach(function () {
    foreach (glob(sys_get_temp_dir().'/archetype-bin-*') as $directory) {
        File::deleteDirectory($directory);
    }
});

it('turns an operation into its artisan command', function () {
    $root = stubApplication();

    [$status, $output] = runBinary($root, 'inspect app/Models/User.php --json');

    expect($status)->toBe(0);
    expect($output)->toBe('archetype:inspect app/Models/User.php --json');
});

it('finds the application from a directory below it', function () {
    $root = stubApplication();

    [, $output] = runBinary($root.'/app/Models', 'inspect app/Models/User.php');

    expect($output)->toBe('archetype:inspect app/Models/User.php');
});

it('lists the operations when given nothing', function () {
    $root = stubApplication();

    [$status, $output] = runBinary($root);

    expect($status)->toBe(0);
    expect($output)->toBe('archetype');
});

it('passes an already prefixed operation through', function () {
    $root = stubApplication();

    [, $output] = runBinary($root, 'archetype:add-case app/Enums/Status.php Draft');

    expect($output)->toBe('archetype:add-case app/Enums/Status.php Draft');
});

it('keeps backslashes in a class name', function () {
    $root = stubApplication();

    [, $output] = runBinary($root, "inspect 'App\\Models\\User'");

    expect($output)->toBe('archetype:inspect App\Models\User');
});

it('says so when there is no application to talk to', function () {
    [$status, $output] = runBinary(sys_get_temp_dir(), 'inspect app/Models/User.php');

    expect($status)->toBe(1);
    expect($output)->toContain('could not find a Laravel artisan file');
});

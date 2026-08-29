<?php

namespace Archetype\Console\Support;

use Archetype\Console\Commands;

/**
 * The console's contract, in one screen.
 *
 * A caller decides how to work before it decides which tool to use, so the
 * whole surface has to be legible in a single, cheap answer. There is
 * deliberately nowhere else to look.
 *
 * The list is in two halves, and the split is the rule the naming follows: a
 * command named after a `PHPFile` or `LaravelFile` endpoint *is* that endpoint,
 * with its directives as flags, and behaves as the PHP API does. A command with
 * a name of its own has no PHP equivalent and is the console's alone.
 */
class Manifest
{
    /** endpoint => [usage, description] — these mirror the PHP API */
    const ENDPOINTS = [
        'property' => ['<target> <name> [<value>] [--add|--remove|--empty|--clear] [--public|--protected|--private]', 'A class property'],
        'className' => ['<target> [<NewName>] [--full]', 'The declared class name'],
        'extends' => ['<target> [<Class>]', 'The parent class'],
        'implements' => ['<target> [<Interface>...] [--add]', 'The interfaces a class implements'],
        'namespace' => ['<target> [<Namespace>] [--remove]', 'The namespace of a file'],
        'use' => ['<target> [<FQCN>...] [--add]', 'The import statements'],
        'useTrait' => ['<target> [<Trait>...] [--add]', 'The traits a class uses'],
        'classConstant' => ['<target> <NAME> [<value>] [--add|--remove|--empty|--clear]', 'A class constant'],
        'methodNames' => ['<target>', 'The names of the declared methods'],
        'fillable' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$fillable'],
        'hidden' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$hidden'],
        'visible' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$visible'],
        'guarded' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$guarded'],
        'unguarded' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$unguarded'],
        'casts' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$casts'],
        'dates' => ['<target> [<value>] [--add|--remove|--empty|--clear]', '$dates'],
        'table' => ['<target> [<value>]', '$table'],
        'connection' => ['<target> [<value>]', '$connection'],
        'timestamps' => ['<target> [<value>]', '$timestamps'],
        'hasOne' => ['<target> <Related> [--name=] [--foreign-key=] [--local-key=]', 'A hasOne relationship'],
        'hasMany' => ['<target> <Related> [--name=] [--foreign-key=] [--local-key=]', 'A hasMany relationship'],
        'belongsTo' => ['<target> <Related> [--name=] [--foreign-key=] [--owner-key=]', 'A belongsTo relationship'],
        'belongsToMany' => ['<target> <Related> [--table=] [--with-pivot=] [--with-timestamps]', 'A belongsToMany relationship'],
        'make' => ['<name> [--file] [--extends=] [--implements=]... [--trait=]...', 'A new file or class'],
        'errors' => ['', 'Files that do not parse'],
    ];

    /** operation => [usage, description] — these have no PHP equivalent */
    const ADDITIONS = [
        'inspect' => ['<target> [meta|traits|uses|consts|cases|props|methods|relations]...', 'Structure of a file, without method bodies'],
        'show' => ['<target> <method>', 'Source of one method'],
        'find' => ['[<dir>] [--type=all|models|controllers|providers|migrations]', 'List files, narrowed by what they are'],
        'set-array-key' => ['<target> <method> <key> [<value>] [--append] [--remove]', 'Edit the array a method returns — rules(), toArray(), casts()'],
        'add-case' => ['<target> <Name> [<value>]', 'Add an enum case'],
        'add-method' => ['<target> --code=<php>', 'Add a method to a class, enum, interface or trait'],
        'replace-method' => ['<target> <name> --code=<php>', 'Replace a method'],
        'remove-method' => ['<target> <name>', 'Remove a method'],
        'apply' => ['[<file>]', 'Run several operations from a script or standard input'],
        'hasOneThrough' => ['<target> <Related> --through=', 'A hasOneThrough relationship'],
        'hasManyThrough' => ['<target> <Related> --through=', 'A hasManyThrough relationship'],
        'morphOne' => ['<target> <Related> --morph-name=', 'A morphOne relationship'],
        'morphMany' => ['<target> <Related> --morph-name=', 'A morphMany relationship'],
        'morphTo' => ['<target> [--morph-name=]', 'A morphTo relationship'],
        'morphToMany' => ['<target> <Related> --morph-name=', 'A morphToMany relationship'],
        'morphedByMany' => ['<target> <Related> --morph-name=', 'A morphedByMany relationship'],
    ];

    /** Relation types the console offers beyond the four LaravelFile endpoints. */
    const EXTRA_RELATIONS = [
        'hasOneThrough', 'hasManyThrough',
        'morphOne', 'morphMany', 'morphTo', 'morphToMany', 'morphedByMany',
    ];

    /** Commands that are one class each. */
    const SINGLETONS = [
        Commands\HelpCommand::class,
        Commands\PropertyCommand::class,
        Commands\ClassNameCommand::class,
        Commands\ExtendsCommand::class,
        Commands\ImplementsCommand::class,
        Commands\NamespaceCommand::class,
        Commands\UseCommand::class,
        Commands\UseTraitCommand::class,
        Commands\ClassConstantCommand::class,
        Commands\MethodNamesCommand::class,
        Commands\MakeCommand::class,
        Commands\InspectCommand::class,
        Commands\ShowCommand::class,
        Commands\FindCommand::class,
        Commands\SetArrayKeyCommand::class,
        Commands\AddCaseCommand::class,
        Commands\AddMethodCommand::class,
        Commands\ReplaceMethodCommand::class,
        Commands\RemoveMethodCommand::class,
        Commands\ApplyCommand::class,
    ];

    /**
     * Everything the service provider registers.
     *
     * The model properties and the relations are one class each, named by a
     * constructor argument, because they are one endpoint underneath and ten
     * near-identical files would say nothing that this does not.
     *
     * `errors` is listed in the map above so it appears in the operation list,
     * but it predates this console and the provider registers it directly.
     *
     * @return array<int, object|class-string>
     */
    public static function commands(): array
    {
        return array_merge(
            self::SINGLETONS,
            array_map(
                fn ($property) => new Commands\ModelPropertyCommand($property),
                array_keys(Commands\ModelPropertyCommand::PROPERTIES)
            ),
            array_map(
                fn ($type) => new Commands\RelationCommand($type),
                array_merge(Commands\RelationCommand::ENDPOINTS, self::EXTRA_RELATIONS)
            ),
        );
    }

    /** Every operation name, in the order the map prints them. */
    public static function operations(): array
    {
        return array_merge(array_keys(self::ENDPOINTS), array_keys(self::ADDITIONS));
    }

    /** @return array<int, string> the operation map, as printed by `archetype` */
    public static function lines(): array
    {
        $width = max(array_map('strlen', self::operations()));

        $render = fn (array $operations) => collect($operations)
            ->map(fn ($operation, $name) => rtrim('  '.str_pad($name, $width).'  '.$operation[0]))
            ->values()
            ->all();

        return array_merge(
            ['These are the PHP API endpoints. Give a value to write, none to read.', ''],
            $render(self::ENDPOINTS),
            ['', 'These have no PHP equivalent.', ''],
            $render(self::ADDITIONS)
        );
    }
}

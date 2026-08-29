<?php

namespace Archetype\Console\Support;

use Archetype\Console\Commands;

/**
 * The console's contract, in one screen.
 *
 * A caller decides how to work before it decides which tool to use, so the
 * whole surface has to be legible in a single, cheap answer. Every command is
 * listed here with its shape; there is deliberately nowhere else to look.
 */
class Manifest
{
    /** operation => [class, usage, description] */
    const OPERATIONS = [
        'inspect' => [
            Commands\InspectCommand::class,
            '<target> [meta|traits|uses|consts|cases|props|methods|relations]...',
            'Structure of a file, without method bodies',
        ],
        'show' => [
            Commands\ShowCommand::class,
            '<target> <method>',
            'Source of one method',
        ],
        'find' => [
            Commands\FindCommand::class,
            '[<dir>] [--type=all|models|controllers|providers|migrations]',
            'List files, narrowed by what they are',
        ],
        'errors' => [
            \Archetype\Commands\ErrorsCommand::class,
            '',
            'Files that do not parse',
        ],
        'make' => [
            Commands\MakeCommand::class,
            '<name> [--file] [--extends=] [--implements=]... [--trait=]...',
            'Create a file or class',
        ],
        'set-property' => [
            Commands\SetPropertyCommand::class,
            '<target> <name> [<json-value>] [--visibility=]',
            'Set a property',
        ],
        'add-to-property' => [
            Commands\AddToPropertyCommand::class,
            '<target> <name> <value>...',
            'Append to an array property, $fillable included',
        ],
        'empty-property' => [
            Commands\EmptyPropertyCommand::class,
            '<target> <name>',
            'Empty a property, keeping the declaration',
        ],
        'remove-property' => [
            Commands\RemovePropertyCommand::class,
            '<target> <name>',
            'Remove a property',
        ],
        'set-casts' => [
            Commands\SetCastsCommand::class,
            '<target> <field>=<cast>...',
            'Set casts, writing to casts() or $casts, whichever the model uses',
        ],
        'add-relation' => [
            Commands\AddRelationCommand::class,
            '<target> <type> [<Related>] [--name=] [--morph-name=] [--through=] [--table=] [--with-pivot=] …',
            'Add an Eloquent relationship, any of the eleven types',
        ],
        'set-array-key' => [
            Commands\SetArrayKeyCommand::class,
            '<target> <method> <key> [<php-expression>] [--append] [--remove]',
            'Edit the array a method returns — rules(), toArray(), casts()',
        ],
        'add-use' => [
            Commands\AddUseCommand::class,
            '<target> <FQCN>...',
            'Add imports',
        ],
        'remove-use' => [
            Commands\RemoveUseCommand::class,
            '<target> <FQCN>...',
            'Remove imports',
        ],
        'add-trait' => [
            Commands\AddTraitCommand::class,
            '<target> <Trait>...',
            'Use a trait, importing it too',
        ],
        'add-implements' => [
            Commands\AddImplementsCommand::class,
            '<target> <Interface>...',
            'Implement interfaces, importing them too',
        ],
        'set-extends' => [
            Commands\SetExtendsCommand::class,
            '<target> <Class>',
            'Set the parent class',
        ],
        'set-namespace' => [
            Commands\SetNamespaceCommand::class,
            '<target> <Namespace>',
            'Set the namespace',
        ],
        'rename-class' => [
            Commands\RenameClassCommand::class,
            '<target> <NewName>',
            'Rename the declared class',
        ],
        'set-const' => [
            Commands\SetConstCommand::class,
            '<target> <NAME> [<json-value>]',
            'Set a class constant',
        ],
        'remove-const' => [
            Commands\RemoveConstCommand::class,
            '<target> <NAME>',
            'Remove a class constant',
        ],
        'add-case' => [
            Commands\AddCaseCommand::class,
            '<target> <Name> [<value>]',
            'Add an enum case',
        ],
        'add-method' => [
            Commands\AddMethodCommand::class,
            '<target> --code=<php>',
            'Add a method to a class, enum, interface or trait',
        ],
        'replace-method' => [
            Commands\ReplaceMethodCommand::class,
            '<target> <name> --code=<php>',
            'Replace a method',
        ],
        'remove-method' => [
            Commands\RemoveMethodCommand::class,
            '<target> <name>',
            'Remove a method',
        ],
        'apply' => [
            Commands\ApplyCommand::class,
            '[<file>]',
            'Run several operations from a script or standard input',
        ],
    ];

    /**
     * The console's own commands.
     *
     * `errors` is listed above so it shows up in the operation map, but it
     * predates this console and the service provider registers it directly, so
     * it is not returned here.
     *
     * @return array<int, class-string>
     */
    public static function commands(): array
    {
        return array_merge(
            [Commands\HelpCommand::class],
            array_values(array_filter(
                array_map(fn ($operation) => $operation[0], self::OPERATIONS),
                fn ($class) => str_starts_with($class, 'Archetype\\Console\\')
            ))
        );
    }

    /** @return array<int, string> */
    public static function lines(): array
    {
        $width = max(array_map('strlen', array_keys(self::OPERATIONS)));

        return collect(self::OPERATIONS)
            ->map(fn ($operation, $name) => rtrim('  '.str_pad($name, $width).'  '.$operation[1]))
            ->values()
            ->all();
    }
}

<?php

namespace Archetype\Console;

use Archetype\Console\Support\Target;
use Symfony\Component\Console\Input\InputOption;

/**
 * A command whose first argument names the files it acts on.
 *
 * The target is a path, a class name, or a directory — and a directory means
 * "every PHP class beneath it", narrowed by the filters below.
 */
abstract class TargetedCommand extends ArchetypeCommand
{
    const TARGET_DESCRIPTION = 'A path (app/Models/User.php), a class name (App\\Models\\User), or a directory (app/Models)';

    /** @return array<int, string> the relative paths this invocation addresses */
    protected function targets(?string $target = null): array
    {
        $paths = Target::resolve($target ?? $this->argument('target'), [
            'extends' => $this->option('extends'),
            'implements' => $this->option('implements'),
            'uses-trait' => $this->option('uses-trait'),
            'matching' => $this->option('matching'),
        ]);

        if (! $paths) {
            throw new \RuntimeException('no files matched '.($target ?? $this->argument('target')));
        }

        return $paths;
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('extends', null, InputOption::VALUE_REQUIRED, 'Only classes extending this (directory targets)'),
            new InputOption('implements', null, InputOption::VALUE_REQUIRED, 'Only classes implementing this (directory targets)'),
            new InputOption('uses-trait', null, InputOption::VALUE_REQUIRED, 'Only classes using this trait (directory targets)'),
            new InputOption('matching', null, InputOption::VALUE_REQUIRED, 'Only paths matching this regular expression (directory targets)'),
        ]);
    }
}

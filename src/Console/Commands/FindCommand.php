<?php

namespace Archetype\Console\Commands;

use Archetype\Console\Support\Target;
use Archetype\Console\TargetedCommand;
use Archetype\Facades\LaravelFile;
use InvalidArgumentException;

/**
 * Structural discovery: which files are there, and which of them are what.
 *
 * The `--type` filters lean on reflection, so they only see classes the
 * application can autoload; `--extends`, `--implements` and `--uses-trait` are
 * answered from the syntax tree and work on anything that parses.
 */
class FindCommand extends TargetedCommand
{
    const TYPES = ['all', 'models', 'controllers', 'providers', 'migrations'];

    protected $signature = 'archetype:find
        {directory? : Where to look, defaulting to app — or to database/migrations with --type=migrations}
        {--type=all : One of all, models, controllers, providers, migrations}';

    protected $description = 'List PHP files, optionally narrowed by what they are';

    protected function perform(): int
    {
        $type = $this->option('type');

        if (! in_array($type, self::TYPES, true)) {
            throw new InvalidArgumentException("unknown --type '$type' — one of ".implode(', ', self::TYPES));
        }

        $directory = $this->argument('directory')
            ?? ($type === 'migrations' ? 'database/migrations' : 'app');

        if (! Target::isDirectory($directory)) {
            throw new InvalidArgumentException("'$directory' is not a directory");
        }

        $paths = $this->query($directory, $type)
            ->map(fn ($file) => Target::relative($file->inputDriver()->absolutePath()))
            ->sort()
            ->values();

        if ($matching = $this->option('matching')) {
            $paths = $paths->filter(fn ($path) => (bool) preg_match('/'.str_replace('/', '\/', $matching).'/', $path))->values();
        }

        $paths->each(fn ($path) => $this->emit($path));
        $this->emit($paths->count().' file(s)');

        $this->payload = ['files' => $paths->all(), 'count' => $paths->count()];

        return self::SUCCESS;
    }

    protected function query(string $directory, string $type)
    {
        $query = LaravelFile::in($directory);

        $query = match ($type) {
            'models' => $query->models(),
            'controllers' => $query->controllers(),
            'providers' => $query->serviceProviders(),
            default => $query,
        };

        if ($extends = $this->option('extends')) {
            $query = $query->where('extends', $extends);
        }

        if ($implements = $this->option('implements')) {
            $query = $query->where('implements', 'contains', $implements);
        }

        if ($trait = $this->option('uses-trait')) {
            $query = $query->where('useTrait', 'contains', $trait);
        }

        return $query->get();
    }
}

<?php

namespace Archetype\Console\Commands;

use Archetype\Console\ArchetypeCommand;
use Archetype\Facades\LaravelFile;
use Archetype\Support\URI;
use RuntimeException;

class MakeCommand extends ArchetypeCommand
{
    protected $signature = 'archetype:make
        {name : A path (app/Models/Car.php) or a class name (App\\Models\\Car)}
        {--file : Create an empty PHP file rather than a class}
        {--extends= : Parent class}
        {--implements=* : Interfaces to implement}
        {--trait=* : Traits to use}
        {--force : Overwrite the file if it already exists}';

    protected $description = 'Create a new PHP file or class';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $path = URI::make($name)->path();

        if (is_file(base_path($path)) && ! $this->option('force')) {
            throw new RuntimeException("$path already exists — pass --force to overwrite it");
        }

        $file = $this->option('file')
            ? LaravelFile::make()->file($name)
            : LaravelFile::make()->class($name);

        if ($parent = $this->option('extends')) {
            $this->importInto($file, [$parent]);
            $file->extends(class_basename($parent));
        }

        if ($interfaces = $this->option('implements')) {
            $this->importInto($file, $interfaces);
            $file->add()->implements(array_map(fn ($name) => class_basename($name), $interfaces));
        }

        if ($traits = $this->option('trait')) {
            $this->importInto($file, $traits);
            $file->add()->useTrait(array_map(fn ($name) => class_basename($name), $traits));
        }

        $source = $file->render();
        $file->save();

        $this->emit("OK $path created");
        $this->emit($source);

        $this->payload = ['ok' => true, 'file' => $path, 'source' => $source];

        return self::SUCCESS;
    }

    protected function importInto($file, array $names): void
    {
        $namespace = (string) $file->namespace();

        $needed = array_values(array_filter($names, function ($name) use ($namespace) {
            $own = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

            return $own !== '' && $own !== $namespace;
        }));

        if ($needed) {
            $file->add()->use($needed);
        }
    }
}

<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile as File;

/** `$file->className()` and `$file->className($name)`. */
class ClassNameCommand extends EndpointCommand
{
    protected $signature = 'archetype:className
        {target : '.self::TARGET_DESCRIPTION.'}
        {name? : The new class name. Omit to read it}';

    protected $description = 'Read or set the name of the class a file declares';

    protected function directives(): array
    {
        return ['full'];
    }

    protected function hasValue(): bool
    {
        return $this->argument('name') !== null;
    }

    protected function get(File $file)
    {
        return $file->className();
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $name = $this->argument('name');

        if ((new Introspector($file))->name() === $name) {
            return $this->unchanged('class name unchanged');
        }

        $file->className($name);

        return "class $name";
    }
}

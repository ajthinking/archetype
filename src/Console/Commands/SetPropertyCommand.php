<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

class SetPropertyCommand extends MutationCommand
{
    protected $signature = 'archetype:set-property
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Property name, without the $}
        {value? : The value, as JSON when it is not a plain string. Omit for a property with no default}
        {--visibility= : public, protected or private. Defaults to the visibility the property already has}';

    protected $description = 'Set a class property, creating it when it is absent';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $raw = $this->argument('value');

        return $this->mutate(function (LaravelFile $file) use ($name, $raw) {
            $this->requireKind($file, ['class']);

            $visibility = $this->visibilityOf($file, $name, $this->option('visibility'));

            if ($this->alreadySet($file, $name, $raw, $visibility)) {
                return $this->unchanged("\$$name unchanged");
            }

            $raw === null
                ? $file->{$visibility}()->setProperty($name)
                : $file->{$visibility}()->property($name, Code::value($raw));

            return "\$$name set";
        });
    }

    protected function alreadySet(LaravelFile $file, string $name, ?string $raw, string $visibility): bool
    {
        foreach ((new Introspector($file))->properties() as $property) {
            if ($property['name'] !== $name) {
                continue;
            }

            return $property['visibility'] === $visibility
                && $property['evaluated']
                && $property['value'] === ($raw === null ? null : Code::value($raw));
        }

        return false;
    }
}

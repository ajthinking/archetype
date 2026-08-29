<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Directives;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile as File;
use Archetype\Support\Types;

/** `$file->classConstant($name)` and `$file->classConstant($name, $value)`. */
class ClassConstantCommand extends EndpointCommand
{
    protected $signature = 'archetype:classConstant
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Constant name}
        {value? : The value, as JSON when it is not a plain string. Omit to read it}';

    protected $description = 'Read or write a class constant';

    protected function directives(): array
    {
        return Directives::WRITING;
    }

    protected function hasValue(): bool
    {
        return $this->argument('value') !== null;
    }

    protected function get(File $file)
    {
        return $file->classConstant($this->argument('name'));
    }

    protected function set(File $file)
    {
        $this->requireKind($file, ['class']);

        $name = $this->argument('name');
        $raw = $this->argument('value');

        if ($outcome = $this->alreadyDone($file, $name, $raw)) {
            return $outcome;
        }

        $this->withDirectives($file)
            ->classConstant($name, $raw === null ? Types::NO_VALUE : Code::value($raw));

        return "const $name ".$this->verb();
    }

    /** @return array<string, mixed>|null */
    protected function alreadyDone(File $file, string $name, ?string $raw): ?array
    {
        $constants = collect((new Introspector($file))->constants());
        $present = $constants->firstWhere('name', $name);

        if (($this->option('remove') || $this->option('empty') || $this->option('clear')) && ! $present) {
            return $this->unchanged("no const $name");
        }

        $writingValue = $raw !== null && ! $this->option('add');

        if ($writingValue && $present && $present['evaluated'] && $present['value'] === Code::value($raw)) {
            return $this->unchanged("$name unchanged");
        }

        return null;
    }

    protected function verb(): string
    {
        return match (true) {
            (bool) $this->option('remove') => 'removed',
            (bool) $this->option('empty') => 'emptied',
            (bool) $this->option('clear') => 'cleared',
            (bool) $this->option('add') => 'added to',
            default => 'set',
        };
    }
}

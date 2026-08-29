<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\LaravelFile;

/**
 * Append to an array property — `$fillable`, `$hidden`, `$dates`, `$with` and
 * everything else of that shape, including one the class does not have yet.
 */
class AddToPropertyCommand extends MutationCommand
{
    protected $signature = 'archetype:add-to-property
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Property name, without the $}
        {values* : Values to append}
        {--visibility= : public, protected or private. Defaults to the visibility the property already has}';

    protected $description = 'Append values to an array property, creating it when it is absent';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $values = $this->argument('values');

        return $this->mutate(function (LaravelFile $file) use ($name, $values) {
            $this->requireKind($file, ['class']);

            $visibility = $this->visibilityOf($file, $name, $this->option('visibility'));
            $existing = $file->property($name);
            $existing = is_array($existing) ? $existing : [];

            $missing = array_values(array_diff($values, $existing));

            if (! $missing) {
                return $this->unchanged("\$$name unchanged");
            }

            $file->assumeType('array')->{$visibility}()->add()->property($name, $missing);

            return "\$$name +".count($missing);
        });
    }
}

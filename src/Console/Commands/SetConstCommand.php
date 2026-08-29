<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;

class SetConstCommand extends MutationCommand
{
    protected $signature = 'archetype:set-const
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Constant name}
        {value? : The value, as JSON when it is not a plain string}';

    protected $description = 'Set a class constant, creating it when it is absent';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $raw = $this->argument('value');

        return $this->mutate(function (LaravelFile $file) use ($name, $raw) {
            $this->requireKind($file, ['class']);

            foreach ((new Introspector($file))->constants() as $constant) {
                if ($constant['name'] === $name && $constant['evaluated'] && $constant['value'] === Code::value($raw)) {
                    return $this->unchanged("$name unchanged");
                }
            }

            $raw === null
                ? $file->setClassConstant($name)
                : $file->classConstant($name, Code::value($raw));

            return "const $name";
        });
    }
}

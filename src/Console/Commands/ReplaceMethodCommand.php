<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\LaravelFile;
use InvalidArgumentException;

class ReplaceMethodCommand extends MutationCommand
{
    protected $signature = 'archetype:replace-method
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : The method to replace}
        {--code= : The new method declaration}';

    protected $description = 'Replace a method with a new declaration';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $code = $this->option('code');

        if (! $code) {
            throw new InvalidArgumentException('--code is required');
        }

        $method = Code::method($code);

        return $this->mutate(function (LaravelFile $file) use ($name, $method) {
            if (! in_array($name, $file->methodNames(), true)) {
                return $this->unchanged("no fn $name");
            }

            $file->astQuery()
                ->classMethod()
                ->where('name->name', $name)
                ->replace(Code::copy($method))
                ->commit()
                ->end();

            return "fn $name replaced";
        });
    }
}

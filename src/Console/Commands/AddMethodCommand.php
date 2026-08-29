<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Member;
use Archetype\LaravelFile;
use InvalidArgumentException;

class AddMethodCommand extends MutationCommand
{
    protected $signature = 'archetype:add-method
        {target : '.self::TARGET_DESCRIPTION.'}
        {--code= : The method declaration, e.g. "public function scopeActive($query) { return $query->where(\'active\', true); }"}';

    protected $description = 'Add a method to a class, enum, interface or trait';

    protected function perform(): int
    {
        $code = $this->option('code');

        if (! $code) {
            throw new InvalidArgumentException('--code is required');
        }

        $method = Code::method($code);
        $name = $method->name->name;

        return $this->mutate(function (LaravelFile $file) use ($method, $name) {
            if (in_array($name, $file->methodNames(), true)) {
                return $this->unchanged("$name exists");
            }

            Member::add($file, Code::copy($method));

            return "fn $name added";
        });
    }
}

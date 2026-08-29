<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\Console\Support\Member;
use Archetype\LaravelFile;
use InvalidArgumentException;
use PhpParser\Node;

class AddCaseCommand extends MutationCommand
{
    protected $signature = 'archetype:add-case
        {target : '.self::TARGET_DESCRIPTION.'}
        {name : Case name}
        {value? : Backing value. Omit for a pure enum}';

    protected $description = 'Add a case to an enum';

    protected function perform(): int
    {
        $name = $this->argument('name');
        $value = $this->argument('value');

        return $this->mutate(function (LaravelFile $file) use ($name, $value) {
            $scope = new Introspector($file);

            if ($scope->kind() !== 'enum') {
                throw new InvalidArgumentException('not an enum, it is a '.$scope->kind());
            }

            if ($scope->hasCase($name)) {
                return $this->unchanged("case $name exists");
            }

            Member::add($file, new Node\Stmt\EnumCase(
                $name,
                $value === null ? null : Code::literal($value)
            ));

            return "case $name";
        });
    }
}

<?php

namespace Archetype\Console\Commands;

use Archetype\Console\EndpointCommand;
use Archetype\LaravelFile as File;
use LogicException;

/** `$file->methodNames()`. Read only, as the endpoint is. */
class MethodNamesCommand extends EndpointCommand
{
    protected $signature = 'archetype:methodNames
        {target : '.self::TARGET_DESCRIPTION.'}';

    protected $description = 'List the names of the methods a file declares';

    protected function directives(): array
    {
        return [];
    }

    protected function hasValue(): bool
    {
        return false;
    }

    protected function get(File $file)
    {
        return $file->methodNames();
    }

    protected function set(File $file)
    {
        throw new LogicException('methodNames is read only');
    }
}

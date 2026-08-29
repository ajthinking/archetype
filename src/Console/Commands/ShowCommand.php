<?php

namespace Archetype\Console\Commands;

use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\Console\TargetedCommand;
use Archetype\Facades\LaravelFile;
use RuntimeException;

/**
 * The source of one method, exactly as written.
 *
 * `archetype:inspect` describes shape and stops at the method signature, but a
 * caller changing `rules()` or `toArray()` needs to see inside one method — and
 * without this it has to read the whole file to get at it.
 */
class ShowCommand extends TargetedCommand
{
    protected $signature = 'archetype:show
        {target : '.self::TARGET_DESCRIPTION.'}
        {method : The method to print}';

    protected $description = 'Print the source of a single method';

    protected function perform(): int
    {
        $method = $this->argument('method');
        $found = [];

        foreach ($this->targets() as $path) {
            $file = LaravelFile::load($path);
            $node = (new Introspector($file))->method($method);

            if (! $node) {
                continue;
            }

            $source = Code::source($file, $node);
            $found[] = ['file' => $path, 'method' => $method, 'source' => $source];

            $this->emit("$path::$method");
            $this->emit($source);
        }

        if (! $found) {
            throw new RuntimeException("no method '$method' in ".$this->argument('target'));
        }

        $this->payload = count($found) === 1 ? $found[0] : ['matches' => $found, 'count' => count($found)];

        return self::SUCCESS;
    }
}

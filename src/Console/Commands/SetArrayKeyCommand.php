<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\ArrayLiteral;
use Archetype\Console\Support\Code;
use Archetype\LaravelFile;
use InvalidArgumentException;
use RuntimeException;

/**
 * Edit the array a method returns.
 *
 * A surprising share of Laravel lives in exactly this shape — `rules()`,
 * `toArray()`, `casts()`, `definition()`, `share()` — and it is the one place
 * the property endpoints cannot reach, because the payload is inside a method
 * body rather than beside it. Without this a caller has to read the whole class
 * to add one key.
 */
class SetArrayKeyCommand extends MutationCommand
{
    protected $signature = 'archetype:set-array-key
        {target : '.self::TARGET_DESCRIPTION.'}
        {method : The method returning the array, e.g. rules or toArray}
        {key : The array key}
        {value? : A plain word is a string; brackets, quotes, $vars, calls, Class::const, numbers and booleans are PHP}
        {--remove : Remove the key instead of setting it}
        {--append : Append the value with no key}';

    protected $description = 'Set, append to or remove a key in the array a method returns';

    protected function perform(): int
    {
        $method = $this->argument('method');
        $key = $this->argument('key');
        $value = $this->argument('value');
        $remove = $this->option('remove');
        $append = $this->option('append');

        if (! $remove && $value === null) {
            throw new InvalidArgumentException('a value is required unless --remove is given');
        }

        return $this->mutate(function (LaravelFile $file) use ($method, $key, $value, $remove, $append) {
            $array = ArrayLiteral::returnedBy($file, $method);

            if (! $array) {
                throw new RuntimeException("$method() does not return an array literal");
            }

            if ($remove) {
                return ArrayLiteral::remove($array, $key)
                    ? "$method()[$key] removed"
                    : $this->unchanged("no $method()[$key]");
            }

            if ($append) {
                return ArrayLiteral::append($array, Code::literal($value))
                    ? "$method() +1"
                    : $this->unchanged("$method() unchanged");
            }

            $outcome = ArrayLiteral::set($array, $key, Code::literal($value));

            return $outcome === 'unchanged'
                ? $this->unchanged("$method()[$key] unchanged")
                : "$method()[$key] $outcome";
        });
    }
}

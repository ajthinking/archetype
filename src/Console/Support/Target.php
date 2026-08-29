<?php

namespace Archetype\Console\Support;

use Archetype\Facades\LaravelFile;
use Archetype\Support\URI;
use InvalidArgumentException;

/**
 * Turns the single `target` argument every console command takes into the list
 * of files it addresses.
 *
 * A target is one of:
 *
 *   app/Models/User.php   a path
 *   App\Models\User       a class name
 *   app/Models            a directory — every PHP class beneath it
 *
 * Having the directory case share the argument rather than live behind an
 * option is what makes `archetype:add-trait app/Models HasFactory` read the
 * same way as the single-file form, with no ambiguity about which positional
 * argument is which.
 */
class Target
{
    /**
     * @param  array{extends?:?string, implements?:?string, uses-trait?:?string, matching?:?string}  $filters
     * @return array<int, string> relative paths, sorted
     */
    public static function resolve(string $target, array $filters = []): array
    {
        if (static::isDirectory($target)) {
            return static::inDirectory($target, $filters);
        }

        foreach (['extends', 'implements', 'uses-trait', 'matching'] as $filter) {
            if (! empty($filters[$filter])) {
                throw new InvalidArgumentException(
                    "--$filter only applies when the target is a directory, and '$target' is not one"
                );
            }
        }

        return [URI::make($target)->path()];
    }

    public static function isDirectory(string $target): bool
    {
        return $target !== '' && is_dir(static::absolute($target));
    }

    /** @return array<int, string> */
    protected static function inDirectory(string $directory, array $filters): array
    {
        $query = LaravelFile::in($directory);

        if ($extends = $filters['extends'] ?? null) {
            $query = $query->where('extends', $extends);
        }

        if ($implements = $filters['implements'] ?? null) {
            $query = $query->where('implements', 'contains', $implements);
        }

        if ($trait = $filters['uses-trait'] ?? null) {
            $query = $query->where('useTrait', 'contains', $trait);
        }

        $paths = $query->get()
            ->map(fn ($file) => static::relative($file->inputDriver()->absolutePath()))
            ->sort()
            ->values()
            ->all();

        if ($matching = $filters['matching'] ?? null) {
            $paths = array_values(array_filter(
                $paths,
                fn ($path) => (bool) preg_match('/'.str_replace('/', '\/', $matching).'/', $path)
            ));
        }

        return $paths;
    }

    public static function relative(string $absolute): string
    {
        return ltrim(str_replace(static::base(), '', $absolute), DIRECTORY_SEPARATOR);
    }

    protected static function absolute(string $relative): string
    {
        return str_starts_with($relative, DIRECTORY_SEPARATOR)
            ? $relative
            : static::base().DIRECTORY_SEPARATOR.trim($relative, DIRECTORY_SEPARATOR);
    }

    protected static function base(): string
    {
        return rtrim(base_path(), DIRECTORY_SEPARATOR);
    }
}

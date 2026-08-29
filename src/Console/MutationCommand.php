<?php

namespace Archetype\Console;

use Archetype\Console\Support\Diff;
use Archetype\Console\Support\Introspector;
use Archetype\Facades\LaravelFile;
use Archetype\LaravelFile as File;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

/**
 * A command that writes.
 *
 * Every mutation goes through this one loop so that three things are true of
 * all of them, whatever they change:
 *
 *   - the file is re-rendered and compared, so a mutation that matched nothing
 *     reports an error instead of a success. Reporting `OK` while writing
 *     nothing is the worst answer to give a caller, because it has no reason to
 *     look again and the mistake surfaces much later;
 *   - the answer carries a diff of what changed, so the caller does not have to
 *     re-read the file to find out;
 *   - a mutation that was already applied is `SKIP`, not `OK` and not an error,
 *     which is what makes these operations safe to repeat.
 */
abstract class MutationCommand extends TargetedCommand
{
    protected int $changed = 0;

    protected int $skipped = 0;

    protected int $failed = 0;

    /**
     * Apply one change to every target.
     *
     * The callback returns a short description of what it did, or the value of
     * `unchanged()` when the file was already in the desired state.
     */
    protected function mutate(callable $work): int
    {
        $targets = $this->targets();
        $differ = new Diff;
        $results = [];

        $this->changed = $this->skipped = $this->failed = 0;

        foreach ($targets as $path) {
            $results[] = $this->mutateOne($path, $work, $differ);
        }

        if (count($targets) > 1) {
            $this->emit(sprintf(
                '%d changed, %d unchanged, %d failed of %d files',
                $this->changed, $this->skipped, $this->failed, count($targets)
            ));
        }

        $this->payload = [
            'ok' => $this->failed === 0,
            'dryRun' => (bool) $this->option('dry-run'),
            'changed' => $this->changed,
            'skipped' => $this->skipped,
            'failed' => $this->failed,
            'results' => $results,
        ];

        return $this->failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    /** Report that the file was already in the desired state. */
    protected function unchanged(string $message): array
    {
        return ['__unchanged' => true, 'message' => $message];
    }

    /** @return array<string, mixed> */
    protected function mutateOne(string $path, callable $work, Diff $differ): array
    {
        try {
            $file = LaravelFile::load($path);
            $before = $file->render();

            $outcome = $work($file, $path);

            $skipped = is_array($outcome) && ($outcome['__unchanged'] ?? false);
            $detail = $skipped ? $outcome['message'] : (string) $outcome;

            $after = $file->render();

            if ($before === $after) {
                return $skipped
                    ? $this->report('SKIP', $path, $detail)
                    : $this->report('ERR', $path, "$detail — but the file did not change");
            }

            if (! $this->option('dry-run')) {
                $file->save();
            }

            return $this->report(
                $this->option('dry-run') ? 'DRY' : 'OK',
                $path,
                $detail,
                $this->option('no-diff') ? '' : $differ->render($before, $after)
            );
        } catch (Throwable $exception) {
            return $this->report('ERR', $path, $exception->getMessage());
        }
    }

    /** @return array<string, mixed> */
    protected function report(string $status, string $path, string $detail, string $diff = ''): array
    {
        match ($status) {
            'SKIP' => $this->skipped++,
            'ERR' => $this->failed++,
            default => $this->changed++,
        };

        $this->emit(trim("$status $path $detail"));

        foreach ($diff === '' ? [] : explode("\n", $diff) as $line) {
            $this->emit($line);
        }

        return array_filter([
            'file' => $path,
            'status' => ['OK' => 'changed', 'DRY' => 'would-change', 'SKIP' => 'unchanged', 'ERR' => 'error'][$status],
            'detail' => $detail,
            'diff' => $diff,
        ], fn ($value) => $value !== '');
    }

    /**
     * Refuse an operation on a construct it does not support.
     *
     * The endpoints this console drives address `class` declarations, so on an
     * enum, interface or trait most of them match nothing. That alone would be
     * caught by the did-anything-change check — but an operation that imports a
     * name before using it writes the import either way, and a file that
     * changed by half looks exactly like a file that changed. Saying no up
     * front is the only version of this that cannot mislead.
     *
     * @param array<int, string> $kinds the constructs the operation supports
     */
    protected function requireKind(File $file, array $kinds): void
    {
        $kind = (new Introspector($file))->kind();

        if (in_array($kind, $kinds, true)) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            '%s only works on %s, and this is %s %s',
            $this->getName(),
            $this->list($kinds),
            in_array($kind[0], ['a', 'e', 'i', 'o', 'u'], true) ? 'an' : 'a',
            $kind
        ));
    }

    /** @param array<int, string> $items */
    protected function list(array $items): string
    {
        $items = array_map(fn ($item) => Str::plural($item), $items);

        return count($items) < 2
            ? $items[0]
            : implode(', ', array_slice($items, 0, -1)).' and '.end($items);
    }

    /**
     * The visibility a property write should use.
     *
     * The property endpoint rewrites the modifiers on every set, defaulting to
     * public, so an operation that says nothing about visibility would quietly
     * widen a protected property. Keeping the one it already has means only an
     * explicit --visibility ever changes it.
     */
    protected function visibilityOf(File $file, string $property, ?string $override = null): string
    {
        if ($override) {
            if (! in_array($override, ['public', 'protected', 'private'], true)) {
                throw new InvalidArgumentException(
                    "--visibility must be public, protected or private, got '$override'"
                );
            }

            return $override;
        }

        foreach ((new Introspector($file))->properties() as $existing) {
            if ($existing['name'] === $property) {
                return $existing['visibility'];
            }
        }

        return 'protected';
    }

    /**
     * Import every fully qualified name not already imported and not already in
     * this file's own namespace. A trait or interface referenced without its
     * import is never valid PHP, so importing is part of the operation rather
     * than a second call the caller has to remember.
     */
    protected function import(File $file, array $names): int
    {
        $namespace = (string) $file->namespace();
        $existing = $file->use();

        $needed = array_values(array_filter($names, function ($name) use ($namespace, $existing) {
            $own = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

            return $own !== '' && $own !== $namespace && ! in_array($name, $existing, true);
        }));

        if ($needed) {
            $file->add()->use($needed);
        }

        return count($needed);
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return array_merge(parent::sharedOptions(), [
            new InputOption('dry-run', null, InputOption::VALUE_NONE, 'Show what would change without writing'),
            new InputOption('no-diff', null, InputOption::VALUE_NONE, 'Suppress the diff a mutation normally answers with'),
        ]);
    }
}

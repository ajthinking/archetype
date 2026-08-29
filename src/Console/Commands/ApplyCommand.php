<?php

namespace Archetype\Console\Commands;

use Archetype\Console\ArchetypeCommand;
use RuntimeException;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Run a script of operations in one invocation.
 *
 * A single change often touches one file several ways — a property, a cast, a
 * scope, an interface — and one call per change means one round trip per
 * change. This collapses them into one, keeping every operation's own
 * verification and diff.
 */
class ApplyCommand extends ArchetypeCommand
{
    protected $signature = 'archetype:apply
        {file? : A file of operations, one per line. Reads standard input when omitted}
        {--stop-on-failure : Stop at the first operation that fails}';

    protected $description = 'Run several archetype operations from a script';

    protected function perform(): int
    {
        $operations = $this->operations();

        if (! $operations) {
            throw new RuntimeException('no operations given');
        }

        $results = [];
        $failed = 0;

        foreach ($operations as $operation) {
            $buffer = new BufferedOutput;
            $status = $this->getApplication()->call($this->normalise($operation), [], $buffer);
            $output = rtrim($buffer->fetch(), "\n");

            $failed += $status === self::SUCCESS ? 0 : 1;
            $results[] = ['operation' => $operation, 'ok' => $status === self::SUCCESS, 'output' => $output];

            foreach (explode("\n", $output) as $line) {
                $this->emit($line);
            }

            if ($status !== self::SUCCESS && $this->option('stop-on-failure')) {
                break;
            }
        }

        $this->emit(sprintf('%d of %d operations ok', count($results) - $failed, count($results)));

        $this->payload = [
            'ok' => $failed === 0,
            'ran' => count($results),
            'failed' => $failed,
            'results' => $results,
        ];

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }

    /** @return array<int, string> */
    protected function operations(): array
    {
        $file = $this->argument('file');

        if ($file !== null && ! is_file($file)) {
            throw new RuntimeException("no such file: $file");
        }

        $script = $file === null ? (string) file_get_contents('php://stdin') : (string) file_get_contents($file);

        return collect(explode("\n", $script))
            ->map(fn ($line) => trim($line))
            ->reject(fn ($line) => $line === '' || str_starts_with($line, '#'))
            ->values()
            ->all();
    }

    /** Operations may be written with or without the `archetype:` prefix. */
    protected function normalise(string $operation): string
    {
        $operation = str_starts_with($operation, 'archetype:') ? $operation : 'archetype:'.$operation;

        return $this->option('json') ? $operation.' --json' : $operation;
    }
}

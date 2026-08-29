<?php

namespace Archetype\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

/**
 * Shared plumbing for every `archetype:*` command.
 *
 * Two conventions hold across the whole console surface, because a caller that
 * cannot rely on them has to verify every answer by reading the file:
 *
 *   - output is deterministic, compact and line oriented, or JSON with --json;
 *   - a command that could not do what was asked exits non-zero and says why.
 */
abstract class ArchetypeCommand extends Command
{
    /** @var array<int, string> */
    protected array $lines = [];

    /** @var array<string, mixed> */
    protected array $payload = [];

    public function __construct()
    {
        parent::__construct();

        foreach ($this->sharedOptions() as $option) {
            $this->getDefinition()->addOption($option);
        }
    }

    /** Do the work. Return an exit code; throwing is equivalent to returning 1. */
    abstract protected function perform(): int;

    public function handle(): int
    {
        // Artisan resolves a command once and reuses the instance, so state
        // from an earlier invocation has to be cleared rather than assumed
        // absent.
        $this->lines = [];
        $this->payload = [];

        try {
            $status = $this->perform();
        } catch (Throwable $exception) {
            return $this->failWith($exception->getMessage());
        }

        $this->flush();

        return $status;
    }

    /** @return array<int, InputOption> */
    protected function sharedOptions(): array
    {
        return [
            new InputOption('json', null, InputOption::VALUE_NONE, 'Emit JSON instead of the compact line format'),
        ];
    }

    protected function emit(string $line): void
    {
        $this->lines[] = $line;
    }

    protected function failWith(string $message): int
    {
        $this->output->writeln(
            $this->option('json')
                ? $this->encode(['ok' => false, 'error' => $message])
                : "ERR $message"
        );

        return self::FAILURE;
    }

    protected function flush(): void
    {
        if ($this->option('json')) {
            $this->output->writeln($this->encode($this->payload));

            return;
        }

        foreach ($this->lines as $line) {
            $this->output->writeln($line);
        }
    }

    protected function encode(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}

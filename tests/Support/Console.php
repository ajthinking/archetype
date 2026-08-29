<?php

namespace Archetype\Tests\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Running a console operation the way a caller does — one command line in, an
 * exit code and the raw output back — rather than through assertion helpers
 * that hide the text an agent would actually read.
 */
class Console
{
    public int $status;

    public string $output;

    public function __construct(string $command, array $arguments = [])
    {
        // A buffer per call: Artisan's own one is shared, so two operations in
        // the same test would otherwise read back as one.
        $buffer = new BufferedOutput;

        $this->status = Artisan::call($command, $arguments, $buffer);
        $this->output = trim($buffer->fetch());
    }

    public static function run(string $command, array $arguments = []): self
    {
        return new self($command, $arguments);
    }

    /** @return array<int, string> */
    public function lines(): array
    {
        return $this->output === '' ? [] : explode("\n", $this->output);
    }

    public function json(): array
    {
        return json_decode($this->output, true) ?? [];
    }

    public function succeeded(): bool
    {
        return $this->status === 0;
    }

    /** Put a file into the application under test. Wiped again by the next test's setUp. */
    public static function write(string $path, string $contents): string
    {
        File::ensureDirectoryExists(dirname(base_path($path)));
        File::put(base_path($path), $contents);

        return $path;
    }

    public static function read(string $path): string
    {
        return File::get(base_path($path));
    }
}

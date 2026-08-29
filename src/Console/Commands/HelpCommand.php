<?php

namespace Archetype\Console\Commands;

use Archetype\Console\ArchetypeCommand;
use Archetype\Console\Support\Manifest;

class HelpCommand extends ArchetypeCommand
{
    protected $signature = 'archetype';

    protected $description = 'List every archetype operation';

    protected function perform(): int
    {
        $this->emit('archetype <operation> [arguments] [options]');
        $this->emit('');

        foreach (Manifest::lines() as $line) {
            $this->emit($line);
        }

        $this->emit('');
        $this->emit('<target> is a path (app/Models/User.php), a class name (App\\Models\\User),');
        $this->emit('or a directory (app/Models) to apply the same change to every class beneath it,');
        $this->emit('narrowed with --extends, --implements, --uses-trait or --matching.');
        $this->emit('');
        $this->emit('Every operation takes --json. Mutations take --dry-run and --no-diff,');
        $this->emit('answer with a diff, skip work already done, and exit non-zero if they');
        $this->emit('could not do what was asked.');

        $describe = fn (array $operations, string $kind) => collect($operations)
            ->map(fn ($operation, $name) => [
                'operation' => $name,
                'usage' => $operation[0],
                'description' => $operation[1],
                'kind' => $kind,
            ])
            ->values()
            ->all();

        $this->payload = [
            'operations' => array_merge(
                $describe(Manifest::ENDPOINTS, 'endpoint'),
                $describe(Manifest::ADDITIONS, 'console')
            ),
        ];

        return self::SUCCESS;
    }
}

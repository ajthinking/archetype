<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPFile;

class ListAPICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archetype:api {--provider=} {--group}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the resource API methods';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->option('group') ? $this->showGrouped() : $this->showAtoZ();
    }

    protected function showGrouped()
    {
        $formattedAPI = $this->api()->map(function ($endpoints, $provider) {
            return collect($endpoints)->map(function ($endpoint) use ($endpoints, $provider) {
                return [
                    $endpoint,
                    'N/A',
                    'N/A'
                ];
            })->toArray();
        });

        $formattedAPI->each(function ($endpoints, $provider) {
            $this->info(PHP_EOL . $provider);
            $this->table(
                ['method', 'parameters', 'description'],
                $endpoints
            );
        });
    }

    protected function showAtoZ()
    {
        Collection::macro('flattenOneLevel', function () {
            return $this->reduce(function ($flattened, $item) {
                return $flattened->concat($item);
            }, collect());
        });

        $formattedAPI = $this->api()->map(function ($endpoints, $provider) {
            return collect($endpoints)->map(function ($endpoint) use ($endpoints, $provider) {
                return [
                    $endpoint,
                    Str::replaceFirst('Archetype\\Endpoints\\', '', $provider),
                ];
            })->toArray();
        })->flattenOneLevel()->sort();

        $this->info(PHP_EOL . 'AVAILABLE ENDPOINTS A-Z');

        $this->table(
            ['method', 'EndpointProvider'],
            $formattedAPI
        );
    }

    protected function api()
    {
        return (new \Archetype\LaravelFile)
            ->endpointProviders()->filter(function ($provider) {
                return ! $this->option('provider') || class_basename($provider) == $this->option('provider');
            })->mapWithKeys(function ($provider, $key) {
                return [
                    $provider => (new $provider())->getEndpoints()
                ];
            });
    }

    protected function documentationExperiment()
    {
        LaravelFile::in('packages/ajthinking/archetype/src/Endpoints')
        ->get()
        ->map->getReflection()
        ->map->getMethods()
        ->flatten()
        ->filter(function ($method) {
            return $method->getModifiers() === 1;
        })
        ->map(function ($method) {
            return (object) [
                'class' => $method->class,
                'name' => $method->name,
                'parameters' => collect($method->getParameters())
                    ->pluck('name')
                    ->toArray(),
                'docblock' => $method->getDocComment()
            ];
        });
    }
}

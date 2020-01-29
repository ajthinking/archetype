<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListAPICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:api {--provider=} {--group}';

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
        $formattedAPI = $this->api()->map(function($endpoints, $provider) {
            return collect($endpoints)->map(function($endpoint) use($endpoints, $provider) {
                return [
                    $endpoint,
                    'N/A',
                    'N/A'
                ];
            })->toArray();
        });

        $formattedAPI->each(function($endpoints, $provider) {
            $this->info(PHP_EOL . $provider);
            $this->table(
                ['method', 'parameters', 'description'],
                $endpoints
            );
        });
    }

    protected function showAtoZ()
    {
        Collection::macro('flattenOneLevel', function() {
            return $this->reduce(function($flattened, $item) {
                return $flattened->concat($item);
            }, collect());
        });

        $formattedAPI = $this->api()->map(function($endpoints, $provider) {
            return collect($endpoints)->map(function($endpoint) use($endpoints, $provider) {
                return [
                    $endpoint,
                    'N/A',
                    'N/A',
                    Str::replaceFirst('PHPFileManipulator\\Endpoints\\', '', $provider),
                ];
            })->toArray();
        })->flattenOneLevel()->sort();

        $this->info(PHP_EOL . 'AVAILABLE ENDPOINTS A-Z');
        $this->table(
            ['method', 'parameters', 'description', 'EndpointProvider'],
            $formattedAPI
        );
    }    

    protected function api()
    {
        return (new \PHPFileManipulator\LaravelFile)
        ->endpointProviders()->filter(function($provider){
            return !$this->option('provider') || class_basename($provider) == $this->option('provider');
        })
        ->mapWithKeys(function ($provider, $key) {
            return [
                $provider => (new $provider())->getEndpoints()
            ];
        });
    }
}

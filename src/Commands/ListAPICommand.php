<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;

class ListAPICommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:api';

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
        $api = (new \PHPFileManipulator\LaravelFile)
            ->endpointProviders()
            ->mapWithKeys(function ($provider, $key) {
                return [
                    $provider => (new $provider())->getEndpoints()
                ];
            });

        

        $formattedAPI = $api->map(function($endpoints, $provider) {
            return collect($endpoints)->map(function($endpoint) use($endpoints, $provider) {
                return [
                    $endpoint,
                    '',
                    $provider
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





        //dd($api);
    }
}

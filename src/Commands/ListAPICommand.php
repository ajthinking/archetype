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
            })->toArray();
            
        dd($api);
    }
}

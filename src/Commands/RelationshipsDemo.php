<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;

use Archetype\Commands\Demo\Project;

class RelationshipsDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archetype:relationships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing relationship methods';

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
        // read our application into a Project class
        $project = new Project();

        $project->missingRelationshipMethods()->each(function ($suggestion) {
            if ($this->confirm("Do you want to add User HasMany Cars?")) {
                $this->info("Cool!");
            }
        });
    }
}

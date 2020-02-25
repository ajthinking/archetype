<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;

use PHPFileManipulator\Commands\Demo\Project;

class RelationshipsDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:relationships';

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
        $p = new Project();
        
        dd(
            $p->schema
        );
    }
}

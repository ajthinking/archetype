<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;

class ListAPICommand extends Command
{
    const PATH_TO_RESOURCES = 'packages/Ajthinking/PHPFileManipulator/src/Resources';

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
        PHPFile::in(static::PATH_TO_RESOURCES)->get()
            ->each(function($file) {
                $this->info(
                    $file->className() . " --> " . 
                    collect($file->classMethodNames())->implode(' | ')
                );
            });


        
    }
}

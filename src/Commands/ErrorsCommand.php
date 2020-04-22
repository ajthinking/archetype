<?php

namespace PHPFileManipulator\Commands;

use Illuminate\Console\Command;
use PHPFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPFileManipulator\Support\Exceptions\FileParseError;

use PHPFileManipulator\Endpoints\PHP\FileQueryBuilder;

class ErrorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List dirty files';

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
        $queryBuilder = new FileQueryBuilder;
            
        $this->errors = collect();

        $this->result = $queryBuilder->recursiveFileSearch('')->map(function($filePath) {
            try {                
                app()->make('PHPFile')->load($filePath);
            } catch(FileParseError $error) {
                $this->errors->push([
                    'path' => $filePath,
                    'message' => $error->original->getMessage()
                ]);
            }
        });
        
        if($this->errors->isEmpty()) return $this->info('No errors found!');

        $this->table(['path', 'message'], $this->errors->toArray());
    }
}

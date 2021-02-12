<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPFile;
use Archetype\Support\Exceptions\FileParseError;

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;

class ErrorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archetype:errors';

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
        $queryBuilder = new PHPFileQueryBuilder;

        $this->errors = collect();

        $filePaths = $queryBuilder->recursiveFileSearch('');

        $this->result = collect($filePaths)->map(function ($filePath) {
            try {
                app()->make('PHPFile')->load($filePath);
            } catch (FileParseError $error) {
                $this->errors->push([
                    'path' => $filePath,
                    'message' => $error->original->getMessage()
                ]);
            }
        });

        if ($this->errors->isEmpty()) {
            return $this->info('No errors found!');
        }

        $this->table(['path', 'message'], $this->errors->toArray());
    }
}

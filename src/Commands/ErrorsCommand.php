<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use Archetype\Support\Exceptions\FileParseError;

use Archetype\Endpoints\PHP\PHPFileQueryBuilder;

class ErrorsCommand extends Command
{
    protected $signature = 'archetype:errors {--json : Emit JSON instead of a table}';
    protected $description = 'List dirty files';
	protected $result;
	protected $errors;

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

        if ($this->option('json')) {
            $this->output->writeln(json_encode([
                'ok' => $this->errors->isEmpty(),
                'errors' => $this->errors->values()->all(),
            ], JSON_UNESCAPED_SLASHES));

            return;
        }

        if ($this->errors->isEmpty()) {
            $this->info('No errors found!');
			return;
        }

        $this->table(['path', 'message'], $this->errors->toArray());
    }
}

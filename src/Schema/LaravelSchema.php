<?php

namespace Archetype\Schema;

use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Archetype\Schema\Types\EnumType;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class LaravelSchema
{
    public $log = [];

    public $strategies = [
        Strategies\FromDatabase::class,
        Strategies\FromFiles::class,
    ];

    public static function get()
    {
        return (new static)->getSchema();
    }

    protected function getSchema()
    {
        // Get schema
        $schema = collect($this->strategies)->reduce(function ($schema, $strategy) {
            // A strategy has already succeded? Return the schema!
            if ($schema) {
                return $schema;
            }
            
            try {
                // Attempt next strategy
                return $strategy::get();
            } catch (\Throwable $e) {
                $this->log[$strategy] = [
                    'message' => $e->getMessage(),
                    'error' => $e->getTraceAsString()
                ];
            }
        }, $schema = false);

        // Prepare response
        if (!$schema) {
            throw new Exception("All schema strategies failed!" . json_encode($this->log));
        }

        $schema->log = $this->log;

        return $schema;
    }
}

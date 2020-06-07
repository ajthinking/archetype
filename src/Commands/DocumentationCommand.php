<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPFile;

class DocumentationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create .md files from docblock annotations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // LaravelFile::in('packages/ajthinking/archetype/src/Endpoints')
        // ->get()
        // ->map->getReflection()
        // ->map->getMethods()
        // ->flatten()
        // ->filter(function ($method) {
        //     return $method->getModifiers() === 1;
        // })
        // ->map(function ($method) {
        //     return (object) [
        //         'class' => $method->class,
        //         'name' => $method->name,
        //         'parameters' => collect($method->getParameters())
        //             ->pluck('name')
        //             ->toArray(),
        //         'docblock' => $method->getDocComment()
        //     ];
        // });
        
        $examples = (new \Archetype\Support\DocumentationExtractor(
            \Archetype\Endpoints\PHP\Namespace_::class, ['example']
        ))->getFromMethod('namespace');

        $examples = collect($examples->tags)->map(function($example) {
            return (object) [
                'comment' => implode(' ', $example['params']),
            ];
        });
        
        $sources = (new \Archetype\Support\DocumentationExtractor(
            \Archetype\Endpoints\PHP\Namespace_::class, ['source']
        ))->getFromMethod('namespace');

        $sources = collect($sources->tags)->map(function($source) {
            return (object) [
                'source' => implode(' ', $source['params']),
            ];
        });

        $chunk = $examples->zip($sources)->map(function($objects) {
            $result = (object) [];
            foreach($objects as $object) {
                $key = array_keys((array)$object)[0];
                $result->$key = $object->$key;
            }
            return $result;
        });

        $r = $chunk->map(function($piece) {
            return '// ' . $piece->comment . PHP_EOL . $piece->source;
        })->implode(PHP_EOL . PHP_EOL);

        file_put_contents(base_path('docs.md'), '```php' . PHP_EOL . $r . PHP_EOL . '```');
    }



}

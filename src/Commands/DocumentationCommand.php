<?php

namespace Archetype\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPFile;
use Illuminate\Support\Facades\File;
use ReflectionException;

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
        $targets = PHPFile::in('packages/ajthinking/archetype/src/Endpoints')->get()
            ->map->getReflection()->filter()->map->name;

        $targets->each(function($target) {
            $doc = $this->getClassDocs($target);

            $path = Str::of(base_path('packages/ajthinking/archetype/docs/'))
                ->append(
                    Str::of($target)
                        ->replaceFirst('Archetype\\', '')
                        ->replace('\\', '/')
                        ->finish('.md')
                );

            File::ensureDirectoryExists(
                dirname($path)
            );
            
            File::put($path, $doc);
        });
    }

    protected function getClassDocs($class)
    {
        try{
            $methodsToDocument = (new $class)->getEndpoints();
        } catch(\Throwable $e) {
            $methodsToDocument = [];
        }

        $extractor = new \Archetype\Support\DocumentationExtractor(
            $class, ['example', 'source']
        );

        $code = collect($methodsToDocument)->map(function($method) use($extractor) {
            try {
                $annotations = collect($extractor->getFromMethod($method));
            } catch(ReflectionException $e) {
                $annotations = collect($extractor->getFromMethod('__call'));
            }

            return $annotations->chunk(2)->map(function($pair) {
                return '// ' . implode(' ', $pair->first()['params']) . PHP_EOL
                    . implode(' ', $pair->last()['params']);
                })->implode(PHP_EOL . PHP_EOL);

        })->implode(PHP_EOL . PHP_EOL);

        return '```php' . PHP_EOL . $code . PHP_EOL . '```';
    }



}

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
    public $all = [];

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
        $this->createMdFiles();
        $this->CombineMdFiles();
    }

    protected function createMDFiles()
    {
        $targets = PHPFile::in('packages/ajthinking/archetype/src/Endpoints')->get()
            ->map->getReflection()->filter()->map->name;

        $targets->each(function($target) {
            $this->createMDFile($target);
        });
    }

    public function createMDFile($target)
    {
        $examples = $this->getEndpointExamples($target);

        $sourceLink = 'https://github.com/ajthinking/archetype/blob/master/src/'
            . Str::of($target)
            ->replaceFirst('Archetype\\', '')
            ->replace('\\', '/')
            ->finish('.php');

        $classBadge = "<a href='$sourceLink'>![$target](https://img.shields.io/badge/-$target-blue)";

        $doc = $classBadge . PHP_EOL
            . '```php' . PHP_EOL . $examples . PHP_EOL . '```'
            . PHP_EOL . '<hr>';

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
        array_push($this->all, $doc);
    }    

    protected function combineMdFiles()
    {
        File::put(
            base_path('packages/ajthinking/archetype/docs/example2.md'),
            collect($this->all)->implode(PHP_EOL . PHP_EOL)
        );
    }

    protected function getEndpointExamples($class)
    {
        try{
            $methodsToDocument = (new $class)->getEndpoints();
        } catch(\Throwable $e) {
            $methodsToDocument = [];
        }

        $extractor = new \Archetype\Support\DocumentationExtractor(
            $class, ['example', 'source']
        );

        $examples = collect($methodsToDocument)->map(function($method) use($extractor) {            

            try {
                $annotations = collect($extractor->getFromMethod($method));
            } catch(ReflectionException $e) {
                $annotations = collect($extractor->getFromMethod('__call'));
            }

            return $annotations->chunk(2)->map(function($pair) {
                return '// ' . implode(' ', $pair->first()['params']) . PHP_EOL
                    . implode(' ', $pair->last()['params']);
                })->implode(PHP_EOL . PHP_EOL);
        })->filter()->implode(PHP_EOL . PHP_EOL);

        return $examples ? $examples : '// UNDOCUMENTED CLASS';
    }



}

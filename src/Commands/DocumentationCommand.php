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
    protected $signature = 'archetype:docs';

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
        $endpoints = PHPFile::in('packages/ajthinking/archetype/src/Endpoints')->get()
            ->map->getReflection()->filter()->map->name;

        $endpointDocs = $endpoints->map(function ($class) {
            // Manually defined class doc?
            if (is_file($this->docFilePath($class))) {
                return file_get_contents(
                    $this->docFilePath($class)
                );
            }

            // Harvested doc
            return $this->makeEndpointDoc($class);
        })->implode(PHP_EOL . PHP_EOL);

        $introduction = '# File read/write API';

        $content = collect([
            $introduction,
            $endpointDocs,
        ])->implode(PHP_EOL . PHP_EOL);
        
        File::put(
            base_path('packages/ajthinking/archetype/docs/api.md'),
            $content
        );
    }

    protected function docFilePath($class)
    {
        return Str::of(base_path('packages/ajthinking/archetype/docs/src/'))
            ->append(
                Str::of($class)
                    ->replaceFirst('Archetype\\', '')
                    ->replace('\\', '/')
                    ->finish('.md')
            );
    }

    public function makeEndpointDoc($class)
    {
        $sourceLink = 'https://github.com/ajthinking/archetype/blob/master/src/'
            . Str::of($class)
            ->replaceFirst('Archetype\\', '')
            ->replace('\\', '/')
            ->finish('.php');

        $classBadge = "<a href='$sourceLink'>![$class](https://img.shields.io/badge/-$class-blue)</a>";
        $classBadge = "[$class]($sourceLink)";
        return $classBadge . PHP_EOL
            . '```php' . PHP_EOL . $this->getEndpointExamples($class) . PHP_EOL . '```'
            . PHP_EOL . '<hr>';
    }

    protected function getEndpointExamples($class)
    {
        try {
            $methodsToDocument = (new $class)->getEndpoints();
        } catch (\Throwable $e) {
            $methodsToDocument = [];
        }

        $extractor = new \Archetype\Support\DocumentationExtractor(
            $class,
            ['example', 'source']
        );

        $examples = collect($methodsToDocument)->map(function ($method) use ($extractor) {

            try {
                $annotations = collect($extractor->getFromMethod($method));
            } catch (ReflectionException $e) {
                $annotations = collect($extractor->getFromMethod('__call'));
            }

            return $annotations->chunk(2)->map(function ($pair) {
                return '// ' . implode(' ', $pair->first()['params']) . PHP_EOL
                    . implode(' ', $pair->last()['params']);
            })->implode(PHP_EOL . PHP_EOL);
        })->filter()->implode(PHP_EOL . PHP_EOL);

        return $examples ? $examples : '// UNDOCUMENTED CLASS';
    }
}

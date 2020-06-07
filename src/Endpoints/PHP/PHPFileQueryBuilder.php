<?php

namespace Archetype\Endpoints\PHP;

use Illuminate\Support\Str;
use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\PSR2PrettyPrinter;
use Archetype\Support\RecursiveFileSearch;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use UnexpectedValueException;
use Archetype\Traits\HasOperators;
use ReflectionClass;
use ReflectionMethod;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;

class PHPFileQueryBuilder extends EndpointProvider
{
    use HasOperators;

    const PHPSignature = '/\.php$/';   
    
    public function __construct($file = null)
    {
        parent::__construct($file);
        $this->result = collect();
    }

    public function query()
    {
        return $this;
    }
    
    public function all()
    {
        return $this->in('')->get();
    }

    public function in($directory)
    {
        $this->baseDir = $directory;

        $this->result = collect($this->recursiveFileSearch($this->baseDir))
            ->map(function($filePath) {
                $type = class_basename($this->file);
                return app()->make($type)->load($filePath);
            });

        return $this;
    }

    /**
     * Supported signatures:
     * where('something', <value>)
     * where('something', <operator> , <value>)
     */
    public function where($arg1, $arg2 = null, $arg3 = null)
    {
        // Ensure we are in a directory context - default to base path
        if(!isset($this->baseDir)) $this->in('');

        // If an array is passed
        if(is_array($arg1)) {
            collect($arg1)->each(function($clause) {
                $this->where(...$clause);
            });
            return $this;
        }

        // If a function is passed
        if(is_callable($arg1)) {
            $this->result = $this->result->filter($arg1);
            return $this;
        }

        // If its a resource where query
        $property = $arg1;
        $operator = $arg3 ? $arg2 : "=";
        $value = $arg3 ? $arg3 : $arg2;

        if (!$this->operatorMethod($operator)) {
            throw new InvalidArgumentException("Operator not supported");
        }

        // Dispatch to HasOperators trait method
        $this->result = $this->result->filter(function($file) use ($property, $operator, $value) {
            $operatorMethod = $this->operatorMethod($operator);

            return $this->$operatorMethod(
                $file->$property(),
                $value
            );
        });

        return $this;
    }

    public function andWhere(...$args)
    {
        return $this->where(...$args);
    }

    public function get()
    {
        // Ensure we are in a directory context - default to base path
        if(!isset($this->baseDir)) $this->in('');
        return $this->result;
    }

    public function first()
    {
        return $this->get()->first();
    }

    public function recursiveFileSearch($directory) {
        $directory = base_path($directory);

        return RecursiveFileSearch::in($directory)
            ->matching(static::PHPSignature)
            ->ignore(config('archetype.ignored_paths'))
            ->get();
    }

    /** this is kept probably because of some inheritance issue */
    protected function getHandlerMethod($signature, $args)
    {
        $reflection = new ReflectionClass(static::class);
        $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))->pluck('name');
        return collect($methods)->contains($signature) ? $signature : false;
    }    
}

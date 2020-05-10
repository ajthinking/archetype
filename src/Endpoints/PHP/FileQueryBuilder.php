<?php

namespace PHPFileManipulator\Endpoints\PHP;

use Illuminate\Support\Str;
use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\PSR2PrettyPrinter;
use PHPFileManipulator\Support\RecursiveFileSearch;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use UnexpectedValueException;
use PHPFileManipulator\Traits\HasOperators;
use ReflectionClass;
use ReflectionMethod;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;

class FileQueryBuilder extends EndpointProvider
{
    use HasOperators;

    const PHPSignature = '/\.php$/';
    
    const operators = [
        // tested
        "=" => "equals",
        "equals" => "equals",
        "!=" => "notEquals",
        "not" => "notEquals",
        "contains" => "contains",
        "has" => "contains",
        "in" => "inOperator",
        "like" => "like",
        "matches" => "matches",
        "count" => "count",
        // untested
        ">" => "greaterThan",
        "<" => "lessThan",
    ];    

    /** this is kept probably because of some inheritance issue */
    protected function getHandlerMethod($signature, $args)
    {
        $reflection = new ReflectionClass(static::class);
        $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))->pluck('name');
        return collect($methods)->contains($signature) ? $signature : false;
    }
    
    public function __construct($file = null)
    {
        parent::__construct($file);
        $this->result = collect();
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
        if(!collect($this::operators)->has($operator)) throw new InvalidArgumentException("Operator not supported");
        $value = $arg3 ? $arg3 : $arg2;

        // Dispatch to HasOperators trait method
        $this->result = $this->result->filter(function($file) use($property, $operator, $value) {
            $operatorMethod = $this::operators[$operator];
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
            ->ignore(config('php-file-manipulator.ignored_paths'))
            ->get();
    }  
}
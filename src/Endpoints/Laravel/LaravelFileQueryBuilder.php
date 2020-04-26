<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use Illuminate\Support\Str;
use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\PSR2PrettyPrinter;
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

use PHPFileManipulator\Endpoints\PHP\FileQueryBuilder;

class LaravelFileQueryBuilder extends FileQueryBuilder
{
    public function user()
    {
        return $this->where('className', 'User')->get()->first();
    }

    public function models()
    {
        return $this->instanceof('Illuminate\Database\Eloquent\Model');
    }

    public function controllers()
    {
        return $this->instanceof('Illuminate\Routing\Controller');
    }

    public function serviceProviders()
    {
        return $this->instanceof('Illuminate\Support\ServiceProvider');
    }    

    // PLEASE TAYLOR MAKE MIGRATIONS NAMESPACED
    // public function migrations()
    // {
    //     return $this->instanceof('Illuminate\Database\Migrations\Migration');
    // }    

    protected function instanceof($class)
    {
        // Ensure we are in a directory context - default to base path
        if(!isset($this->baseDir)) $this->in('');

        $this->result = $this->result->filter(function($file) use($class) {
            $reflection = $file->getReflection();
            return $reflection && $reflection->isSubclassOf($class);
        });

        return $this;
    }
}
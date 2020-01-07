<?php

namespace Ajthinking\PHPFileManipulator\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;

class QueryBuilder
{
    static $PHPSignature = '/\.php$/';
    
    const EQUALS = "=";

    public function __construct()
    {
        $this->result = collect();
    }

    public function all()
    {
        $this->recursiveFileSearch('', static::$PHPSignature);
    }

    public function in($directory)
    {
        $this->baseDir = $directory;

        $this->result = $this->recursiveFileSearch($this->baseDir)->map(function($filePath) { 
            return LaravelFile::load($filePath);
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

        // If a function is passed
        if(is_callable($arg1)) {
            $this->result = $this->result->filter($arg1);
            return $this;
        }

        // If its a resource where query
        $property = $arg1;
        $operator = $arg3 ? $arg2 : $this::EQUALS;
        if($operator != $this::EQUALS) throw new InvalidArgumentException("Only EQUALS operator supported rigth now.");
        $value = $arg3 ? $arg3 : $arg2;

        $this->result = $this->result->filter(function($file) use($property, $value) {
            return $file->$property() == $value;
        });

        return $this;
    }

    public function get()
    {
        return $this->result;
    }
    
    private function recursiveFileSearch($directory) {
        $directory = base_path($directory);

        // Will exclude everything under these directories
        $exclude = array('vendor', '.git', 'node_modules');
        
        /**
         * @param SplFileInfo $file
         * @param mixed $key
         * @param RecursiveCallbackFilterIterator $iterator
         * @return bool True if you need to recurse or if the item is acceptable
         */
        $filter = function ($file, $key, $iterator) use ($exclude) {
            // Exclude some folders
            if (in_array($file->getFilename(), $exclude)) {
                return false;
            }

            // Iterate recursively
            if ($iterator->hasChildren()) {
                return true;
            }

            // Accept any file matching signature
            return $file->isFile() && preg_match(static::$PHPSignature, $file->getFilename());
        };
        
        $innerIterator = new RecursiveDirectoryIterator(
            $directory,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );
        
        foreach ($iterator as $pathname => $fileInfo) {
            // do your insertion here
        }

        return collect(iterator_to_array($iterator))->map(function($file) {
            return $file->getFilename();
        })->keys();
    }
}
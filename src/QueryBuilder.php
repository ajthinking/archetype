<?php

namespace Ajthinking\PHPFileManipulator;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RecursiveCallbackFilterIterator;

use Ajthinking\PHPFileManipulator\LaravelFile;

class QueryBuilder
{
    public function __construct()
    {
        $this->result = collect();    
        $this->PHPSignature = '/\.php$/';
    }

    public function all()
    {
        $PHPSignature = '/\.php$/';
        $JSONSignature = '/\.json$/';

        $this->recursiveFileSearch(
            base_path(),
            $PHPSignature
        );
    }

    public function in($directory)
    {
        $this->result = $this->recursiveFileSearch($directory)->map(function($filePath) {
            return new LaravelFile($filePath);
        });        
        return $this;    
    }
    
    public function where($args)
    {
        // resource query
        // filename query
        // function query
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
            // Iterate recursively - except excludes folder
            if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
                return true;
            }

            // Accept any file matching signature
            return $file->isFile() && preg_match($this->PHPSignature, $file->getFilename());
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
<?php

namespace Archetype\Support;

use Illuminate\Support\Str;
use Archetype\Endpoints\EndpointProvider;
use Archetype\Support\PSR2PrettyPrinter;
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

class RecursiveFileSearch
{
    protected $ignore = [];

    protected $pattern;

    protected $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public static function in($directory)
    {
        return new static($directory);
    }

    public function ignore($paths)
    {
        $this->ignore = $paths;

        return $this;
    }

    public function matching($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function get()
    {
        /**
         * @param SplFileInfo $file
         * @param mixed $key
         * @param RecursiveCallbackFilterIterator $iterator
         * @return bool True if you need to recurse or if the item is acceptable
         */
        $filter = function ($file, $key, $iterator) {
            // Exclude some folders/files
            $exclude = $this->ignore;
            if (in_array($file->getFilename(), $exclude)) {
                return false;
            }

            // Iterate recursively
            if ($iterator->hasChildren()) {
                return true;
            }

            // Accept any file matching optional signature
            return $file->isFile() &&
                ($this->pattern ? preg_match($this->pattern, $file->getFilename()) : true);
        };

        $innerIterator = new RecursiveDirectoryIterator(
            $this->directory,
            RecursiveDirectoryIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );

        return array_keys(iterator_to_array($iterator));
    }
}

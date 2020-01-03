<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Ajthinking\PHPFileManipulator\PHPFile;
use Ajthinking\PHPFileManipulator\PSR2PrettyPrinter;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;

trait HasIO
{
    public function __construct($relativePath)
    {
        $this->path = base_path($relativePath);
        $this->relativePath = $relativePath;
        
        $this->contents = file_get_contents($this->path);
        // ast - abstract syntax tree
        $this->ast = $this->parse();
    }

    static function load($relativePath)
    {
        return new static(
            $relativePath
        );
    }    

    public function path()
    {
        return $this->path;
    }

    public function relativePath($newRelativePath = false)
    {
        if($newRelativePath) {
            $this->path = base_path($newRelativePath);
            $this->relativePath = $newRelativePath;
        }

        return $this->relativePath;
    }        

    public function save($path = false)
    {
        // optionally update path
        if($path) $this->path = $path;

        // write current ast to file
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->ast);
        file_put_contents($this->path, $code);

        return $this;
    }
    
    public function preview()
    {
        Storage::put(".preview/$this->relativePath", $this->print());
        return $this;
    }

    public function ast()
    {
        return $this->ast;
    }    

    public function parse()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($this->contents);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        return $ast;
    }
    
    public function print()
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        return $prettyPrinter->prettyPrintFile($this->ast);
    }    
}
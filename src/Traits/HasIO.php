<?php

namespace Ajthinking\PHPFileManipulator\Traits;

use Ajthinking\PHPFileManipulator\PSR2PrettyPrinter;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use Illuminate\Support\Str;
use UnexpectedValueException;

trait HasIO
{
    public function load($path)
    {
        $this->path = Str::startsWith($path, '/') ? $path : base_path($path);
        
        $this->contents = file_get_contents($this->path);
        
        $this->ast = $this->parse();        

        return $this;
    }
    
    public function fromString($code)
    {        
        $this->contents = $code;

        $this->path = null;
        
        $this->ast = $this->parse();        

        return $this;        
    }

    public function path()
    {
        return $this->path;
    }        

    public function save($path = false)
    {
        // optionally update path
        if($path) $this->path = $path;

        // write current ast to file
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->ast);

        if(!$this->path) throw new UnexpectedValueException('Could not save because we dont have a path!');

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
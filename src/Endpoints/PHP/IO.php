<?php

namespace PHPFileManipulator\Endpoints\PHP;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\Endpoint;
use PHPFileManipulator\Support\PSR2PrettyPrinter;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use UnexpectedValueException;
use Config;
use PHPFileManipulator\Support\PHPFileStorage;

class IO extends Endpoint
{
    public function getHandlerMethod($signature, $args)
    {
        return collect([
            "ast",
            "dd",
            "fromString",
            "load",
            "parse",
            "path",
            "preview",
            "print",
            "save",
            "setInputRoot",
            "setOutputRoot",
            "setDebugRoot",

        ])->contains($signature) ? $signature : false;
    }

    public function load($path)
    {
        $this->file->path = PHPFileStorage::fullInputPath($path);
        $this->file->contents = PHPFileStorage::get($this->file->path);
        $this->file->ast = $this->parse();        
        return $this->file;
    }
    
    public function fromString($code)
    {        
        $this->file->contents = $code;
        $this->file->path = null;
        $this->file->ast = $this->parse();        
        return $this->file;        
    }

    public function path()
    {
        return $this->file->path;
    }
    
    public function relativePath()
    {
        return PHPFileStorage::relativeInputPath($this->file->path);
    }    

    public function save($path = false)
    {
        // optionally update path
        if($path) $this->file->path = PHPFileStorage::fullOutputPath($path);
        
        // unknown path - might be a file created from a string
        if(!$this->file->path) throw new UnexpectedValueException('Could not save because we dont have a path!');

        // write current ast to file
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->file->ast);

        PHPFileStorage::put($this->file->path, $code);
        return $this->file;
    }

    public function setDebugRoot($path = false)
    {
        $path = $path ? $path : Config::get("php-file-manipulator.roots.debug.root");
        Config::set("php-file-manipulator.roots.output.root", $path);
        
        return $this->file;
    }

    public function setInputRoot($path)
    {
        Config::set("php-file-manipulator.roots.input.root", $path);        

        return $this->file;
    }
    
    public function setOutputRoot($path)
    {
        Config::set("php-file-manipulator.roots.output.root", $path);        
        return $this->file;
    }    

    public function ast()
    {
        return $this->file->ast;
    }    

    public function parse()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($this->file->contents);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        return $ast;
    }
    
    public function print()
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        return $prettyPrinter->prettyPrintFile($this->file->ast);
    }
    
    public function dd($method = false)
    {
        dd(
            $method ? $this->file->$method() : $this
        );
    }
}
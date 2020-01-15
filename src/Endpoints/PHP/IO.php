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

        ])->contains($signature) ? $signature : false;
    }

    public function load($path)
    {
        $this->file->path = $this->fullInputPath($path);
        $this->file->relativePath = Str::replaceFirst(
            $this->getStorageRootPath('input'),
            '',
            $this->file->path
        );

        $this->file->contents = $this->getStorageDisk('input')->get($this->file->relativePath);
        
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

    public function save($path = false)
    {
        // optionally update path
        if($path) $this->file->path = $this->fullOutputPath($path);

        // write current ast to file
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->file->ast);

        if(!$this->file->path) throw new UnexpectedValueException('Could not save because we dont have a path!');

        $this->file->relativePath = Str::replaceFirst(
            $this->getStorageRootPath('output'),
            '',
            $this->file->path
        );

        $this->getStorageDisk('output')->put($this->file->relativePath, $code);

        return $this->file;
    }

    public function debug()
    {

    }

    public function setInputRoot()
    {
        
    }
    
    public function setOutputRoot()
    {
        
    }    
    
    public function preview()
    {
        Storage::put(".preview/" . $this->file->relativePath, $this->print());
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
    
    private function getStorageDisk($name)
    {        
        $disk = Config::get("php-file-manipulator.roots.$name");

        Config::set("filesystems.disks.roots.$name", $disk);

        return Storage::disk("roots.$name");
    }

    private function getStorageRootPath($name)
    {
        return Config::get("php-file-manipulator.roots.$name.root");
    }
    
    private function fullInputPath($path)
    {
        return Str::startsWith($path, '/') ? $path 
        : $this->getStorageRootPath('input') . "/$path";
    }

    private function fullOutputPath($path)
    {
        return Str::startsWith($path, '/') ? $path 
        : $this->getStorageRootPath('output') . "/$path";
    }    
}
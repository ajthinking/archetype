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
use PHPFileManipulator\PHPFile;
use PHPFileManipulaot\Support\Path;

class IO extends Endpoint
{
    public function __construct(PHPFile $file)
    {
        parent::__construct($file);
        $this->file->roots = config('php-file-manipulator.roots');
    }

    public function getHandlerMethod($signature, $args)
    {
        return collect([
            "ast",
            "dd",
            "fromString",
            "load",
            "parse",
            "inputPath",
            "inputName",
            "inputDir",
            "path",
            "outputPath",
            "preview",
            "print",
            "debug",
            "save",
            "setInputRoot",
            "setOutputRoot",
            "setDebugRoot",

        ])->contains($signature) ? $signature : false;
    }

    public function load($path)
    {
        $this->file->inputPath = Path::make($path)->withRoot($this->roots['input']);
        $this->file->inputPath = PHPFileStorage::fullInputPath($path);
        $this->file->inputName = basename($path);   
        $this->file->contents = PHPFileStorage::get($this->file->inputPath);
        $this->file->ast = $this->parse();                
        
        $this->setOutputPath();        
        
        return $this->file;
    }
    
    public function fromString($code)
    {        
        $this->file->contents = $code;
        $this->file->inputPath = null;        
        $this->file->ast = $this->parse();


        $this->setOutputPath();
        return $this->file;        
    }

    public function inputName()
    {
        return basename($this->file->inputPath);
    }

    public function inputDir()
    {
        return dirname($this->file->inputPath);
    }    

    public function inputPath()
    {
        return $this->file->inputPath;
    }
    
    public function relativeInputPath()
    {
        return PHPFileStorage::relativeInputPath($this->file->inputPath);
    }    

    public function save($outputPath = false)
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->file->ast);

        $this->setOutputPath($outputPath);
        if(!$this->file->outputPath) throw new UnexpectedValueException('Could not save because we dont have a path!');

        PHPFileStorage::put(
            $this->file->outputPath,
            $code
        );
    
        return $this->file;
    }

    protected function setOutputPath($outputPath = false)
    {
        if($outputPath) {
            return $this->file->outputPath = PHPFileStorage::fullOutputPath($outputPath);
        }
        
        if($this->relativeInputPath()) {
            return $this->file->outputPath = PHPFileStorage::fullOutputPath($this->relativeInputPath());
        }

        $this->file->outputPath = null;
    }

    public function outputPath()
    {
        return $this->file->outputPath;
    }

    public function debug($path = false)
    {
        $this->file->roots['output'] = $this->file->roots['debug'];
        return $this->setDebugRoot($path)->save();
    }

    public function setInputRoot($path)
    {
        $this->file->roots['input'] = $path;        
        return $this->file;
    }
    
    public function setOutputRoot($path)
    {
        $this->file->roots['output'] = $path;        
        return $this->file;
    }
    
    public function setDebugRoot($path)
    {
        $this->file->roots['debug'] = $path;        
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
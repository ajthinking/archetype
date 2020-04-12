<?php

namespace PHPFileManipulator\Endpoints\PHP;

use Illuminate\Support\Str;
use PHPFileManipulator\Support\EndpointProvider;
use PHPFileManipulator\Support\PSR2PrettyPrinter;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use UnexpectedValueException;
use Config;
use PHPFileManipulator\Support\PHPFileStorage;
use PHPFileManipulator\PHPFile;
use PHPFileManipulator\Support\Path;

class IO extends EndpointProvider
{
    public function __construct(PHPFile $file = null)
    {
        parent::__construct($file);
        $this->storage = new PHPFileStorage(
            config('php-file-manipulator.roots')
        );
    }

    public function getHandlerMethod($signature, $args)
    {
        return collect([
            "ast",
            "dd",
            "fromString",
            "hasModifications",
            "load",
            "parse",
            "relativeInputPath",
            "inputPath",
            "inputName",
            "inputDir",
            "path",
            "outputPath",
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
        $this->file->inputPath = Path::make($path)->withDefaultRoot($this->root('input'))->full();
        $this->file->contents($this->storage->get($this->file->inputPath));
        $this->file->ast($this->parse());
        $this->file->initialModificationHash = $this->getModificationHash();
        $this->setOutputPath();        
        
        return $this->file;
    }
    
    public function fromString($code)
    {        
        $this->file->inputPath = null;        
        $this->file->contents($code);
        $this->file->ast($this->parse());
        $this->file->initialModificationHash = $this->getModificationHash();
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
        return $this->file->inputPath ?
            Path::make($this->file->inputPath)->relative($this->root('input'))
            : null;
    }    

    public function save($outputPath = false)
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->file->ast());

        $this->setOutputPath($outputPath);
        if(!$this->file->outputPath) throw new UnexpectedValueException('Could not save because we dont have a path!');

        $this->storage->put(
            $this->file->outputPath,
            $code
        );
    
        return $this->file;
    }

    protected function setOutputPath($outputPath = false)
    {
        if($outputPath) {
            return $this->file->outputPath = Path::make($outputPath)->withDefaultRoot($this->root('output'))->full();
        }
        
        if($this->relativeInputPath()) {
            return $this->file->outputPath = Path::make($this->relativeInputPath())
                ->withDefaultRoot($this->root('output'))->full();
        }

        $this->file->outputPath = null;
    }

    public function outputPath()
    {
        return $this->file->outputPath;
    }

    public function debug()
    {
        $this->storage->roots['output'] = $this->storage->roots['debug'];
        return $this->save();
    }

    public function ast()
    {
        return $this->file->ast();
    }    

    public function parse()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($this->file->contents());
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        return $ast;
    }

    public function hasModifications()
    {

        return ($this->file->ast() == null) || $this->getModificationHash() != $this->file->initialModificationHash;
    }

    protected function getModificationHash()
    {
        return md5(json_encode($this->file->ast()));
    }
    
    public function print()
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        return $prettyPrinter->prettyPrintFile($this->file->ast());
    }
    
    public function dd($method = false)
    {
        dd(
            $method ? $this->file->$method() : $this
        );
    }
    
    private function root($name)
    {
        return $this->storage->roots[$name]['root'];
    }

    public function contents($content = null)
    {
        //
    }
}
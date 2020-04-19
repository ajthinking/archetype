<?php

namespace PHPFileManipulator\Traits;

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

trait HasIO
{
    
    public function __construct()
    {
        $this->storage = new PHPFileStorage;
    }

    public function inputDriver($driver = null)
    {
        if(!$driver) return $this->input;

        $this->input = $driver;

        return $this;
    }

    public function outputDriver($driver = null)
    {
        if(!$driver) return $this->output;

        $this->output = $driver;

        return $this;
    }    

    public function load($path)
    {        
        // Proposed new solution - this trait only forwards to the driver implementation:
        $content = $this->input->load($path);
        $this->contents($content);

        $this->ast($this->parse());


        $this->inputPath = Path::make($path)->withDefaultRoot($this->root('input'))->full();
        $this->initialModificationHash = $this->getModificationHash();
        $this->setOutputPath();        
        
        return $this;
    }
    
    public function fromString($code)
    {        
        $this->inputPath = null;        
        $this->contents($code);
        $this->ast($this->parse());
        $this->initialModificationHash = $this->getModificationHash();
        $this->setOutputPath();
        
        return $this;        
    }
    
    public function relativeInputPath()
    {
        return $this->inputPath ?
            Path::make($this->inputPath)->relative($this->root('input'))
            : null;
    }    

    public function save($outputPath = false)
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        $code = $prettyPrinter->prettyPrintFile($this->ast());

        $this->setOutputPath($outputPath);
        
        if(!$this->outputPath) throw new UnexpectedValueException('Could not save because we dont have a path!');

        $this->output->save($this->outputPath, $code);
    
        return $this;
    }

    protected function setOutputPath($outputPath = false)
    {
        if($outputPath) {
            return $this->outputPath = Path::make($outputPath)->withDefaultRoot($this->root('output'))->full();
        }
        
        if($this->relativeInputPath()) {
            return $this->outputPath = Path::make($this->relativeInputPath())
                ->withDefaultRoot($this->root('output'))->full();
        }

        $this->outputPath = null;
    }

    public function outputPath()
    {
        return $this->outputPath;
    }

    public function debug()
    {
        $this->storage->roots['output'] = $this->storage->roots['debug'];
        $this->output->storage->roots['output'] = $this->output->storage->roots['debug'];
        $this->output->root = $this->storage->roots['debug'];

        return $this->save();
    }

    public function parse()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse($this->contents());
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        return $ast;
    }

    public function hasModifications()
    {
        return ($this->ast() == null) || $this->getModificationHash() != $this->initialModificationHash;
    }

    protected function getModificationHash()
    {
        return md5(json_encode($this->ast()));
    }
    
    public function print()
    {
        $prettyPrinter = new PSR2PrettyPrinter;
        return $prettyPrinter->prettyPrintFile($this->ast());
    }
    
    public function dd($method = false)
    {
        dd(
            $method ? $this->$method() : $this
        );
    }
    
    private function root($name)
    {
        return $this->storage->roots[$name]['root'];
    }

    public function contents($contents = false)
    {
        return $contents ? $this->contents = $contents : $this->contents;
    }

    public function ast($ast = false)
    {
        return $ast ? $this->ast = $ast : $this->ast;
    }
}
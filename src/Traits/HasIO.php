<?php

namespace PHPFileManipulator\Traits;

use Illuminate\Support\Str;
use PHPFileManipulator\Endpoints\EndpointProvider;
use PHPFileManipulator\Support\PSR2PrettyPrinter;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use PHPParser\Error as PHPParserError;
use PHPFileManipulator\Support\Exceptions\FileParseError;
use UnexpectedValueException;
use Config;
use PHPFileManipulator\Support\PHPFileStorage;
use PHPFileManipulator\PHPFile;
use PHPFileManipulator\Support\Path;

trait HasIO
{
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

    public function find($path)
    {
        return $this->load($path);
    }

    public function load($path)
    {   
        $content = $this->input->load($path);

        $this->output->setDefaultsFrom($this->input);
        
        $this->contents($content);
        
        $this->ast($this->parse());

        $this->initialModificationHash = $this->getModificationHash();
        
        return $this;
    }
    
    public function fromString($code)
    {        
        $this->contents($code);
        $this->ast($this->parse());
        $this->initialModificationHash = $this->getModificationHash();
        
        return $this;        
    }

    public function save($outputPath = false)
    {
        $this->output->save($outputPath, $this->render());
    
        return $this;
    }

    public function debug()
    {
        $this->output->storage->roots['output'] = $this->output->storage->roots['debug'];
        $this->output->root = config('php-file-manipulator.roots')['debug'];

        return $this->save();
    }

    public function preview()
    {
        dd($this->render());
    }

    public function render()
    {
        return (new PSR2PrettyPrinter)->prettyPrintFile($this->ast());
    }

    public function parse()
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $ast = $parser->parse($this->contents());
        } catch (PHPParserError $error) {
            // rethrow with extra information
            throw new FileParseError($this->input->absolutePath(), $error);
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

    public function contents($contents = false)
    {
        return $contents ? $this->contents = $contents : $this->contents;
    }

    public function ast($ast = false)
    {
        if(!$ast) return $this->ast;
        $this->ast = $ast;
        return $this;
    }
}
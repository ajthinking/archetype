<?php

namespace Archetype\Traits;

use Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Archetype\Endpoints\EndpointProvider;
use Archetype\PHPFile;
use Archetype\Support\Exceptions\FileParseError;
use Archetype\Support\Path;
use Archetype\Support\PHPFileStorage;
use Archetype\Support\PSR2PrettyPrinter;
use PHPParser\Error as PHPParserError;
use PhpParser\ParserFactory;
use UnexpectedValueException;

trait HasIO
{
    public function inputDriver($driver = null)
    {
        if (! $driver) {
            return $this->input;
        }

        $this->input = $driver;

        return $this;
    }

    public function outputDriver($driver = null)
    {
        if (! $driver) {
            return $this->output;
        }

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
        $this->output->root = config('archetype.roots')['debug'];

        return $this->save();
    }

    public function preview()
    {
        echo $this->render();
    }

    public function render()
    {
        //$newCode = $printer->printFormatPreserving($newStmts, $oldStmts, $oldTokens);
        return (new PSR2PrettyPrinter)->prettyPrintFile($this->ast());
    }

    public function parse()
    {
        // $lexer = new \PhpParser\Lexer\Emulative([
        //     'usedAttributes' => [
        //         'comments',
        //         'startLine', 'endLine',
        //         'startTokenPos', 'endTokenPos',
        //     ],
        // ]);

        // $parser = new \PhpParser\Parser\Php7($lexer);

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

    public function ast($ast = null)
    {
        if ($ast === null) {
            return $this->ast;
        }

        $this->ast = $ast;

        return $this;
    }
}

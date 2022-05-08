<?php

namespace Archetype\Traits;

use Archetype\Support\Exceptions\FileParseError;
use Archetype\Support\PSR2PrettyPrinter;
use PhpParser\Error as PHPParserError;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\CloningVisitor;

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

    public function load(string $location)
    {
        $content = $this->input->load($location);

        $this->output->setDefaultsFrom($this->input);

        $this->contents = $content;

        $this->ast($this->parse());

        $this->originalAst = $this->ast();

        $this->initialModificationHash = $this->getModificationHash();

        return $this;
    }

    public function fromString($code)
    {
		$code = $this->prepareCode($code);
		
        $this->contents = $code;

        $this->ast($this->parse());

        $this->originalAst = $this->ast();

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

		return $this;
    }

    public function parse()
    {
        $this->lexer = new \PhpParser\Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);

        $parser = new \PhpParser\Parser\Php7($this->lexer);

        //$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $traverser = new NodeTraverser();
        $traverser->addVisitor(new CloningVisitor());
        
        try {
            $this->originalAst = $parser->parse($this->contents());
            $this->tokens = $this->lexer->getTokens();
        } catch (PHPParserError $error) {
            // rethrow with extra information
            throw new FileParseError($this->input->absolutePath(), $error);
        }

        $ast = $traverser->traverse($this->originalAst);

        return $ast;
    }


    public function render()
    {
        //return (new PSR2PrettyPrinter)->prettyPrintFile($this->ast());

        // This wrecks AST (slightly)
        return (new PSR2PrettyPrinter)->printFormatPreserving(
            $this->ast(),
            $this->originalAst,
            $this->tokens
        );
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

    public function contents()
    {
		return $this->contents;
    }	

    public function ast($ast = null)
    {
        if ($ast === null) {
            return $this->ast;
        }

        $this->ast = $ast;

        return $this;
    }

	protected function prepareCode($code)
	{		
		if(!$this->directive('addMissingTags')) return $code;

		if(!str_contains($code, '<?php')) {
			$code = '<?php ' . PHP_EOL . PHP_EOL . $code;
		}

		if(!preg_match_all("/[\};]\s*$/", $code)) {
			$code .= ';';
		}

		return $code;
	}
}

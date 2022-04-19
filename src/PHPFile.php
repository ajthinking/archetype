<?php

namespace Archetype;

use Archetype\Endpoints\PHP\ClassConstant;
use Archetype\Endpoints\PHP\ClassName;
use Archetype\Endpoints\PHP\Extends_;
use Archetype\Endpoints\PHP\Implements_;
use Archetype\Endpoints\PHP\MethodNames;
use Archetype\Endpoints\PHP\Namespace_;
use Archetype\Endpoints\PHP\Property;
use Archetype\Endpoints\PHP\Use_;
use Archetype\Support\AST\ASTQueryBuilder;
use Archetype\Support\Types;
use Archetype\Traits\HasDirectives;
use Archetype\Traits\HasDirectiveHandlers;
use Archetype\Traits\HasIO;
use BadMethodCallException;

class PHPFile
{
    use HasIO;
    use HasDirectives;
    use HasDirectiveHandlers;

    protected $input;

    protected $output;

    protected string $contents;

    protected string $fileQueryBuilder = Endpoints\PHP\PHPFileQueryBuilder::class;

	public string $astQueryBuilder = ASTQueryBuilder::class;

    protected $ast;

    protected string $initialModificationHash;

    protected $originalAst;

    protected $tokens;

    protected $lexer;

    protected $directives = [];

    protected const endpointProviders = [
        Endpoints\PHP\AstQuery::class,
        Endpoints\PHP\Make::class,		
        Endpoints\PHP\ReflectionProxy::class,
        Endpoints\SyntacticSweetener::class,
    ];

    public function __construct(
        string $input = \Archetype\Drivers\FileInput::class,
        string $output = \Archetype\Drivers\FileOutput::class
    ) {
        $this->input = $input;
        $this->output = $output;
    }

    public function __call($method, $args)
    {
        $handler = $this->endpointProviders()->filter(function ($endpoint) use ($method, $args) {
            return (new $endpoint($this))->canHandle($method, $args);
        })->first();

        if ($handler) {
            return (new $handler($this))->$method(...$args);
        }

        throw new BadMethodCallException("Could not find a handler for method $method");
    }	

    public function endpointProviders()
    {
        return collect(self::endpointProviders)->push(
            $this->fileQueryBuilder
        );
    }

	public function property($key, $value = Types::NO_VALUE)
	{
		$handler = new Property($this);
		return $handler->property($key, $value);
	}

    public function setProperty($key, $value = Types::NO_VALUE)
    {
		$handler = new Property($this);
		return $handler->setProperty($key, $value);		
    }
	
	public function use($value = null)
	{
		$handler = new Use_($this);
		return $handler->use($value);
	}

	public function namespace(string $value = null)
	{
		$handler = new Namespace_($this);
		return $handler->namespace($value);
	}

	public function methodNames()
	{
		$handler = new MethodNames($this);
		return $handler->methodNames();
	}
	
	public function implements($name = null)
	{
		$handler = new Implements_($this);
		return $handler->implements($name);
	}

	public function extends($name = null)
	{
		$handler = new Extends_($this);
		return $handler->extends($name);
	}
	
	public function className($name = null)
	{
		$handler = new ClassName($this);
		return $handler->className($name);
	}
	
	public function classConstant($key, $value = Types::NO_VALUE)
	{
		$handler = new ClassConstant($this);
		return $handler->classConstant($key, $value);
	}

	public function setClassConstant($key, $value = Types::NO_VALUE)
	{
		$handler = new ClassConstant($this);
		return $handler->setClassConstant($key, $value);
	}	
}

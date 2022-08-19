<?php

namespace Archetype;

use Archetype\Drivers\InputInterface;
use Archetype\Drivers\OutputInterface;
use Archetype\Endpoints\PHP\AstQuery;
use Archetype\Endpoints\PHP\ClassConstant;
use Archetype\Endpoints\PHP\ClassName;
use Archetype\Endpoints\PHP\Extends_;
use Archetype\Endpoints\PHP\Implements_;
use Archetype\Endpoints\Maker;
use Archetype\Endpoints\PHP\MethodNames;
use Archetype\Endpoints\PHP\Namespace_;
use Archetype\Endpoints\PHP\Property;
use Archetype\Endpoints\PHP\ReflectionProxy;
use Archetype\Endpoints\PHP\Use_;
use Archetype\Endpoints\PHP\UseTrait;
use Archetype\Support\AST\ASTQueryBuilder;
use Archetype\Support\Types;
use Archetype\Traits\HasDirectives;
use Archetype\Traits\HasDirectiveHandlers;
use Archetype\Traits\HasIO;
use Archetype\Traits\HasSyntacticSweeteners;

class PHPFile
{
	use HasIO;
	use HasDirectives;
	use HasDirectiveHandlers;
	use HasSyntacticSweeteners;

	public InputInterface $input;

	public OutputInterface $output;

	protected string $contents;

	protected string $fileQueryBuilder = Endpoints\PHP\PHPFileQueryBuilder::class;

	protected Maker $maker;

	public string $astQueryBuilder = ASTQueryBuilder::class;

	protected $ast;

	protected string $initialModificationHash;

	protected $originalAst;

	protected $tokens;

	protected $lexer;

	protected $directives = [];

	public function __construct(
		InputInterface $input,
		OutputInterface $output,
		Maker $maker
	) {
		$this->input = $input;
		$this->output = $output;
		$this->maker = $maker;
	}

	public function query()
	{
		return new $this->fileQueryBuilder($this);
	}

	public function all(...$args)
	{
		return $this->query()->all(...$args);
	}

	public function in(...$args)
	{
		return $this->query()->in(...$args);
	}

	public function where(...$args)
	{
		return $this->query()->where(...$args);
	}

	public function astQuery()
	{
		$handler = new AstQuery($this);
		return $handler->astQuery();
	}

	public function getReflection()
	{
		$handler = new ReflectionProxy($this);
		return $handler->getReflection();
	}

	public function make()
	{
		return $this->maker->withFile($this);
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

	public function useTrait($value = null)
	{
		$handler = new UseTrait($this);
		return $handler->useTrait($value);
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

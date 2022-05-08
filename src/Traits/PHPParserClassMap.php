<?php

namespace Archetype\Traits;

trait PHPParserClassMap
{
	protected $phpParserClassMap = [
		'arg' => \PhpParser\Node\Arg::class,
		'array' => \PhpParser\Node\Expr\Array_::class,
		'arrayDimFetch' => \PhpParser\Node\Expr\ArrayDimFetch::class,
		'arrayItem' => \PhpParser\Node\Expr\ArrayItem::class,
		'arrowFunction' => \PhpParser\Node\Expr\ArrowFunction::class,
		'assign' => \PhpParser\Node\Expr\Assign::class,
		'assignOp' => \PhpParser\Node\Expr\AssignOp::class,
		'assignRef' => \PhpParser\Node\Expr\AssignRef::class,
		'binaryOp' => \PhpParser\Node\Expr\BinaryOp::class,
		'bitwiseNot' => \PhpParser\Node\Expr\BitwiseNot::class,
		'booleanNot' => \PhpParser\Node\Expr\BooleanNot::class,
		'break' => \PhpParser\Node\Stmt\Break_::class,
		'case' => \PhpParser\Node\Stmt\Case_::class,
		'cast' => \PhpParser\Node\Expr\Cast::class,
		'catch' => \PhpParser\Node\Stmt\Catch_::class,
		'class' => \PhpParser\Node\Stmt\Class_::class,
		'classConst' => \PhpParser\Node\Stmt\ClassConst::class,
		'classConstFetch' => \PhpParser\Node\Expr\ClassConstFetch::class,
		'classLike' => \PhpParser\Node\Stmt\ClassLike::class,
		'classMethod' => \PhpParser\Node\Stmt\ClassMethod::class,
		'clone' => \PhpParser\Node\Expr\Clone_::class,
		'closure' => \PhpParser\Node\Expr\Closure::class,
		'closureUse' => \PhpParser\Node\Expr\ClosureUse::class,
		'const' => \PhpParser\Node\Const_::class, // one of potentially many consts in the same declaration
		'constStmt' => \PhpParser\Node\Stmt\Const_::class, // a node statement ouside of a class. NOTE NAME CHANGE!
		'constFetch' => \PhpParser\Node\Expr\ConstFetch::class,
		'continue' => \PhpParser\Node\Stmt\Continue_::class,
		'declare' => \PhpParser\Node\Stmt\Declare_::class,
		'declareDeclare' => \PhpParser\Node\Stmt\DeclareDeclare::class,
		'dNumber' => \PhpParser\Node\Scalar\DNumber::class,
		'do' => \PhpParser\Node\Stmt\Do_::class,
		'echo' => \PhpParser\Node\Stmt\Echo_::class,
		'else' => \PhpParser\Node\Stmt\Else_::class,
		'elseIf' => \PhpParser\Node\Stmt\ElseIf_::class,
		'empty' => \PhpParser\Node\Expr\Empty_::class,
		'encapsed' => \PhpParser\Node\Scalar\Encapsed::class,
		'encapsedStringPart' => \PhpParser\Node\Scalar\EncapsedStringPart::class,
		'error' => \PhpParser\Node\Expr\Error::class,
		'errorSuppress' => \PhpParser\Node\Expr\ErrorSuppress::class,
		'eval' => \PhpParser\Node\Expr\Eval_::class,
		'exit' => \PhpParser\Node\Expr\Exit_::class,
		'expression' => \PhpParser\Node\Stmt\Expression::class,
		'finally' => \PhpParser\Node\Stmt\Finally_::class,
		'for' => \PhpParser\Node\Stmt\For_::class,
		'foreach' => \PhpParser\Node\Stmt\Foreach_::class,
		'fullyQualified' => \PhpParser\Node\Name\FullyQualified::class,
		'funcCall' => \PhpParser\Node\Expr\FuncCall::class,
		'function' => \PhpParser\Node\Stmt\Function_::class,
		'global' => \PhpParser\Node\Stmt\Global_::class,
		'goto' => \PhpParser\Node\Stmt\Goto_::class,
		'groupUse' => \PhpParser\Node\Stmt\GroupUse::class,
		'haltCompiler' => \PhpParser\Node\Stmt\HaltCompiler::class,
		'if' => \PhpParser\Node\Stmt\If_::class,
		'include' => \PhpParser\Node\Expr\Include_::class,
		'inlineHTML' => \PhpParser\Node\Stmt\InlineHTML::class,
		'instanceof' => \PhpParser\Node\Expr\Instanceof_::class,
		'interface' => \PhpParser\Node\Stmt\Interface_::class,
		'isset' => \PhpParser\Node\Expr\Isset_::class,
		'label' => \PhpParser\Node\Stmt\Label::class,
		'list' => \PhpParser\Node\Expr\List_::class,
		'lNumber' => \PhpParser\Node\Scalar\LNumber::class,
		'magicConst' => \PhpParser\Node\Scalar\MagicConst::class,
		'methodCall' => \PhpParser\Node\Expr\MethodCall::class,
		'name' => \PhpParser\Node\Name::class,
		'namespace' => \PhpParser\Node\Stmt\Namespace_::class,
		'new' => \PhpParser\Node\Expr\New_::class,
		'nop' => \PhpParser\Node\Stmt\Nop::class,
		'postDec' => \PhpParser\Node\Expr\PostDec::class,
		'postInc' => \PhpParser\Node\Expr\PostInc::class,
		'preDec' => \PhpParser\Node\Expr\PreDec::class,
		'preInc' => \PhpParser\Node\Expr\PreInc::class,
		'print' => \PhpParser\Node\Expr\Print_::class,
		'property' => \PhpParser\Node\Stmt\Property::class,
		'propertyFetch' => \PhpParser\Node\Expr\PropertyFetch::class,
		'propertyProperty' => \PhpParser\Node\Stmt\PropertyProperty::class,
		'relative' => \PhpParser\Node\Name\Relative::class,
		'return' => \PhpParser\Node\Stmt\Return_::class,
		'shellExec' => \PhpParser\Node\Expr\ShellExec::class,
		'static' => \PhpParser\Node\Stmt\Static_::class,
		'staticCall' => \PhpParser\Node\Expr\StaticCall::class,
		'staticPropertyFetch' => \PhpParser\Node\Expr\StaticPropertyFetch::class,
		'staticVar' => \PhpParser\Node\Stmt\StaticVar::class,
		'string' => \PhpParser\Node\Scalar\String_::class,
		'switch' => \PhpParser\Node\Stmt\Switch_::class,
		'ternary' => \PhpParser\Node\Expr\Ternary::class,
		'throw' => \PhpParser\Node\Stmt\Throw_::class,
		'trait' => \PhpParser\Node\Stmt\Trait_::class,
		'traitUse' => \PhpParser\Node\Stmt\TraitUse::class,
		'traitUseAdaptation' => \PhpParser\Node\Stmt\TraitUseAdaptation::class,
		'tryCatch' => \PhpParser\Node\Stmt\TryCatch::class,
		'unaryMinus' => \PhpParser\Node\Expr\UnaryMinus::class,
		'unaryPlus' => \PhpParser\Node\Expr\UnaryPlus::class,
		'unset' => \PhpParser\Node\Stmt\Unset_::class,
		'use' => \PhpParser\Node\Stmt\Use_::class,
		'useUse' => \PhpParser\Node\Stmt\UseUse::class,
		'variable' => \PhpParser\Node\Expr\Variable::class,
		'while' => \PhpParser\Node\Stmt\While_::class,
		'yield' => \PhpParser\Node\Expr\Yield_::class,
		'yieldFrom' => \PhpParser\Node\Expr\YieldFrom::class,
	];

	public function arg($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function array($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function arrayDimFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function arrayItem($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function arrowFunction($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function assign($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function assignOp($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function assignRef($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function binaryOp($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function bitwiseNot($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function booleanNot($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function break($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function case($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function cast($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function catch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function class($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function classConst($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function classConstFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function classLike($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function classMethod($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function clone($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function closure($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function closureUse($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function const($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	 // one of potentially many consts in the same declaration
	public function constStmt($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	 // a node statement ouside of a class. NOTE NAME CHANGE!
	public function constFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function continue($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function declare($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function declareDeclare($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function dNumber($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function do($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function echo($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function else($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function elseIf($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function empty($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function encapsed($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function encapsedStringPart($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function error($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function errorSuppress($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function eval($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function exit($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function expression($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function finally($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function for($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function foreach($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function fullyQualified($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function funcCall($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function function($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function global($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function goto($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function groupUse($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function haltCompiler($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function if($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function include($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function inlineHTML($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function instanceof($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function interface($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function isset($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function label($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function list($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function lNumber($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function magicConst($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function methodCall($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function name($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function namespace($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function new($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function nop($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function postDec($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function postInc($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function preDec($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function preInc($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function print($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function property($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function propertyFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function propertyProperty($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function relative($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function return($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function shellExec($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function static($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function staticCall($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function staticPropertyFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function staticVar($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function string($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function switch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function ternary($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function throw($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function trait($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function traitUse($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function traitUseAdaptation($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function tryCatch($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function unaryMinus($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function unaryPlus($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function unset($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function use($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function useUse($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function variable($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function while($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function yield($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
	
	public function yieldFrom($path = ''): self
	{
		return $this->traverseIntoClass(
			$this->phpParserClassMap[__FUNCTION__],
			$path
		);
	}
}

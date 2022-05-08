<?php

namespace Archetype\Traits;

trait PHPParserClassMap
{
	public function arg($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Arg::class,
			$path
		);
	}
	
	public function array($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Array_::class,
			$path
		);
	}
	
	public function arrayDimFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ArrayDimFetch::class,
			$path
		);
	}
	
	public function arrayItem($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ArrayItem::class,
			$path
		);
	}
	
	public function arrowFunction($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ArrowFunction::class,
			$path
		);
	}
	
	public function assign($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Assign::class,
			$path
		);
	}
	
	public function assignOp($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\AssignOp::class,
			$path
		);
	}
	
	public function assignRef($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\AssignRef::class,
			$path
		);
	}
	
	public function binaryOp($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\BinaryOp::class,
			$path
		);
	}
	
	public function bitwiseNot($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\BitwiseNot::class,
			$path
		);
	}
	
	public function booleanNot($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\BooleanNot::class,
			$path
		);
	}
	
	public function break($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Break_::class,
			$path
		);
	}
	
	public function case($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Case_::class,
			$path
		);
	}
	
	public function cast($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Cast::class,
			$path
		);
	}
	
	public function catch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Catch_::class,
			$path
		);
	}
	
	public function class($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Class_::class,
			$path
		);
	}
	
	public function classConst($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\ClassConst::class,
			$path
		);
	}
	
	public function classConstFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ClassConstFetch::class,
			$path
		);
	}
	
	public function classLike($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\ClassLike::class,
			$path
		);
	}
	
	public function classMethod($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\ClassMethod::class,
			$path
		);
	}
	
	public function clone($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Clone_::class,
			$path
		);
	}
	
	public function closure($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Closure::class,
			$path
		);
	}
	
	public function closureUse($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ClosureUse::class,
			$path
		);
	}
	
	public function const($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Const_::class,
			$path
		);
	}
	 // one of potentially many consts in the same declaration
	public function constStmt($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Const_::class,
			$path
		);
	}
	 // a node statement ouside of a class. NOTE NAME CHANGE!
	public function constFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ConstFetch::class,
			$path
		);
	}
	
	public function continue($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Continue_::class,
			$path
		);
	}
	
	public function declare($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Declare_::class,
			$path
		);
	}
	
	public function declareDeclare($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\DeclareDeclare::class,
			$path
		);
	}
	
	public function dNumber($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\DNumber::class,
			$path
		);
	}
	
	public function do($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Do_::class,
			$path
		);
	}
	
	public function echo($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Echo_::class,
			$path
		);
	}
	
	public function else($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Else_::class,
			$path
		);
	}
	
	public function elseIf($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\ElseIf_::class,
			$path
		);
	}
	
	public function empty($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Empty_::class,
			$path
		);
	}
	
	public function encapsed($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\Encapsed::class,
			$path
		);
	}
	
	public function encapsedStringPart($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\EncapsedStringPart::class,
			$path
		);
	}
	
	public function error($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Error::class,
			$path
		);
	}
	
	public function errorSuppress($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ErrorSuppress::class,
			$path
		);
	}
	
	public function eval($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Eval_::class,
			$path
		);
	}
	
	public function exit($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Exit_::class,
			$path
		);
	}
	
	public function expression($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Expression::class,
			$path
		);
	}
	
	public function finally($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Finally_::class,
			$path
		);
	}
	
	public function for($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\For_::class,
			$path
		);
	}
	
	public function foreach($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Foreach_::class,
			$path
		);
	}
	
	public function fullyQualified($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Name\FullyQualified::class,
			$path
		);
	}
	
	public function funcCall($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\FuncCall::class,
			$path
		);
	}
	
	public function function($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Function_::class,
			$path
		);
	}
	
	public function global($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Global_::class,
			$path
		);
	}
	
	public function goto($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Goto_::class,
			$path
		);
	}
	
	public function groupUse($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\GroupUse::class,
			$path
		);
	}
	
	public function haltCompiler($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\HaltCompiler::class,
			$path
		);
	}
	
	public function if($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\If_::class,
			$path
		);
	}
	
	public function include($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Include_::class,
			$path
		);
	}
	
	public function inlineHTML($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\InlineHTML::class,
			$path
		);
	}
	
	public function instanceof($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Instanceof_::class,
			$path
		);
	}
	
	public function interface($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Interface_::class,
			$path
		);
	}
	
	public function isset($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Isset_::class,
			$path
		);
	}
	
	public function label($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Label::class,
			$path
		);
	}
	
	public function list($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\List_::class,
			$path
		);
	}
	
	public function lNumber($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\LNumber::class,
			$path
		);
	}
	
	public function magicConst($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\MagicConst::class,
			$path
		);
	}
	
	public function methodCall($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\MethodCall::class,
			$path
		);
	}
	
	public function name($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Name::class,
			$path
		);
	}
	
	public function namespace($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Namespace_::class,
			$path
		);
	}
	
	public function new($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\New_::class,
			$path
		);
	}
	
	public function nop($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Nop::class,
			$path
		);
	}
	
	public function postDec($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\PostDec::class,
			$path
		);
	}
	
	public function postInc($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\PostInc::class,
			$path
		);
	}
	
	public function preDec($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\PreDec::class,
			$path
		);
	}
	
	public function preInc($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\PreInc::class,
			$path
		);
	}
	
	public function print($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Print_::class,
			$path
		);
	}
	
	public function property($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Property::class,
			$path
		);
	}
	
	public function propertyFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\PropertyFetch::class,
			$path
		);
	}
	
	public function propertyProperty($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\PropertyProperty::class,
			$path
		);
	}
	
	public function relative($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Name\Relative::class,
			$path
		);
	}
	
	public function return($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Return_::class,
			$path
		);
	}
	
	public function shellExec($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\ShellExec::class,
			$path
		);
	}
	
	public function static($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Static_::class,
			$path
		);
	}
	
	public function staticCall($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\StaticCall::class,
			$path
		);
	}
	
	public function staticPropertyFetch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\StaticPropertyFetch::class,
			$path
		);
	}
	
	public function staticVar($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\StaticVar::class,
			$path
		);
	}
	
	public function string($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Scalar\String_::class,
			$path
		);
	}
	
	public function switch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Switch_::class,
			$path
		);
	}
	
	public function ternary($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Ternary::class,
			$path
		);
	}
	
	public function throw($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Throw_::class,
			$path
		);
	}
	
	public function trait($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Trait_::class,
			$path
		);
	}
	
	public function traitUse($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\TraitUse::class,
			$path
		);
	}
	
	public function traitUseAdaptation($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\TraitUseAdaptation::class,
			$path
		);
	}
	
	public function tryCatch($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\TryCatch::class,
			$path
		);
	}
	
	public function unaryMinus($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\UnaryMinus::class,
			$path
		);
	}
	
	public function unaryPlus($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\UnaryPlus::class,
			$path
		);
	}
	
	public function unset($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Unset_::class,
			$path
		);
	}
	
	public function use($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\Use_::class,
			$path
		);
	}
	
	public function useUse($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\UseUse::class,
			$path
		);
	}
	
	public function variable($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Variable::class,
			$path
		);
	}
	
	public function while($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Stmt\While_::class,
			$path
		);
	}
	
	public function yield($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\Yield_::class,
			$path
		);
	}
	
	public function yieldFrom($path = ''): self
	{
		return $this->traverseIntoClass(
			\PhpParser\Node\Expr\YieldFrom::class,
			$path
		);
	}
}

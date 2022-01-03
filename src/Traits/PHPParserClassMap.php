<?php

namespace Archetype\Traits;

trait PHPParserClassMap
{
    public function classMap($class = null)
    {
        $map = [
            // CAMELCASEIFIED WITHOUT _ POSTFIX
            'array' => '\PhpParser\Node\Expr\Array_',
            'arrayDimFetch' => '\PhpParser\Node\Expr\ArrayDimFetch',
            'arrayItem' => '\PhpParser\Node\Expr\ArrayItem',
            'arrowFunction' => '\PhpParser\Node\Expr\ArrowFunction',
            'assign' => '\PhpParser\Node\Expr\Assign',
            'assignOp' => '\PhpParser\Node\Expr\AssignOp',
            'assignOp' => '\PhpParser\Node\Expr\AssignOp',
            'assignRef' => '\PhpParser\Node\Expr\AssignRef',
            'binaryOp' => '\PhpParser\Node\Expr\BinaryOp',
            'binaryOp' => '\PhpParser\Node\Expr\BinaryOp',
            'bitwiseNot' => '\PhpParser\Node\Expr\BitwiseNot',
            'booleanNot' => '\PhpParser\Node\Expr\BooleanNot',
            'break' => '\PhpParser\Node\Stmt\Break_',
            'case' => '\PhpParser\Node\Stmt\Case_',
            'cast' => '\PhpParser\Node\Expr\Cast',
            'cast' => '\PhpParser\Node\Expr\Cast',
            'catch' => '\PhpParser\Node\Stmt\Catch_',
            'class' => '\PhpParser\Node\Stmt\Class_',
            'classConst' => '\PhpParser\Node\Stmt\ClassConst',
            'classConstFetch' => '\PhpParser\Node\Expr\ClassConstFetch',
            'classLike' => '\PhpParser\Node\Stmt\ClassLike',
            'classMethod' => '\PhpParser\Node\Stmt\ClassMethod',
            'clone' => '\PhpParser\Node\Expr\Clone_',
            'closure' => '\PhpParser\Node\Expr\Closure',
            'closureUse' => '\PhpParser\Node\Expr\ClosureUse',
			'const' => '\PhpParser\Node\Const_', // REVIEW
			'constStmt' => '\PhpParser\Node\Stmt\Const_', // REVIEW
            'constFetch' => '\PhpParser\Node\Expr\ConstFetch',
            'continue' => '\PhpParser\Node\Stmt\Continue_',
            'declare' => '\PhpParser\Node\Stmt\Declare_',
            'declareDeclare' => '\PhpParser\Node\Stmt\DeclareDeclare',
            'dNumber' => '\PhpParser\Node\Scalar\DNumber',
            'do' => '\PhpParser\Node\Stmt\Do_',
            'echo' => '\PhpParser\Node\Stmt\Echo_',
            'else' => '\PhpParser\Node\Stmt\Else_',
            'elseIf' => '\PhpParser\Node\Stmt\ElseIf_',
            'empty' => '\PhpParser\Node\Expr\Empty_',
            'encapsed' => '\PhpParser\Node\Scalar\Encapsed',
            'encapsedStringPart' => '\PhpParser\Node\Scalar\EncapsedStringPart',
            'error' => '\PhpParser\Node\Expr\Error',
            'errorSuppress' => '\PhpParser\Node\Expr\ErrorSuppress',
            'eval' => '\PhpParser\Node\Expr\Eval_',
            'exit' => '\PhpParser\Node\Expr\Exit_',
            'expression' => '\PhpParser\Node\Stmt\Expression',
            'finally' => '\PhpParser\Node\Stmt\Finally_',
            'for' => '\PhpParser\Node\Stmt\For_',
            'foreach' => '\PhpParser\Node\Stmt\Foreach_',
            'fullyQualified' => '\PhpParser\Node\Name\FullyQualified',
            'funcCall' => '\PhpParser\Node\Expr\FuncCall',
            'function' => '\PhpParser\Node\Stmt\Function_',
            'global' => '\PhpParser\Node\Stmt\Global_',
            'goto' => '\PhpParser\Node\Stmt\Goto_',
            'groupUse' => '\PhpParser\Node\Stmt\GroupUse',
            'haltCompiler' => '\PhpParser\Node\Stmt\HaltCompiler',
            'if' => '\PhpParser\Node\Stmt\If_',
            'include' => '\PhpParser\Node\Expr\Include_',
            'inlineHTML' => '\PhpParser\Node\Stmt\InlineHTML',
            'instanceof' => '\PhpParser\Node\Expr\Instanceof_',
            'interface' => '\PhpParser\Node\Stmt\Interface_',
            'isset' => '\PhpParser\Node\Expr\Isset_',
            'label' => '\PhpParser\Node\Stmt\Label',
            'list' => '\PhpParser\Node\Expr\List_',
            'lNumber' => '\PhpParser\Node\Scalar\LNumber',
            'magicConst' => '\PhpParser\Node\Scalar\MagicConst',
            'magicConst' => '\PhpParser\Node\Scalar\MagicConst',
            'methodCall' => '\PhpParser\Node\Expr\MethodCall',
            'name' => 'PhpParser\Node\Name',
            'namespace' => '\PhpParser\Node\Stmt\Namespace_',
            'new' => '\PhpParser\Node\Expr\New_',
            'nop' => '\PhpParser\Node\Stmt\Nop',
            'postDec' => '\PhpParser\Node\Expr\PostDec',
            'postInc' => '\PhpParser\Node\Expr\PostInc',
            'preDec' => '\PhpParser\Node\Expr\PreDec',
            'preInc' => '\PhpParser\Node\Expr\PreInc',
            'print' => '\PhpParser\Node\Expr\Print_',
            'property' => '\PhpParser\Node\Stmt\Property',
            'propertyFetch' => '\PhpParser\Node\Expr\PropertyFetch',
            'propertyProperty' => '\PhpParser\Node\Stmt\PropertyProperty',
            'relative' => '\PhpParser\Node\Name\Relative',
            'return' => '\PhpParser\Node\Stmt\Return_',
            'shellExec' => '\PhpParser\Node\Expr\ShellExec',
            'static' => '\PhpParser\Node\Stmt\Static_',
            'staticCall' => '\PhpParser\Node\Expr\StaticCall',
            'staticPropertyFetch' => '\PhpParser\Node\Expr\StaticPropertyFetch',
            'staticVar' => '\PhpParser\Node\Stmt\StaticVar',
            'string' => '\PhpParser\Node\Scalar\String_',
            'switch' => '\PhpParser\Node\Stmt\Switch_',
            'ternary' => '\PhpParser\Node\Expr\Ternary',
            'throw' => '\PhpParser\Node\Stmt\Throw_',
            'trait' => '\PhpParser\Node\Stmt\Trait_',
            'traitUse' => '\PhpParser\Node\Stmt\TraitUse',
            'traitUseAdaptation' => '\PhpParser\Node\Stmt\TraitUseAdaptation',
            'tryCatch' => '\PhpParser\Node\Stmt\TryCatch',
            'unaryMinus' => '\PhpParser\Node\Expr\UnaryMinus',
            'unaryPlus' => '\PhpParser\Node\Expr\UnaryPlus',
            'unset' => '\PhpParser\Node\Stmt\Unset_',
            'use' => '\PhpParser\Node\Stmt\Use_',
            'useUse' => '\PhpParser\Node\Stmt\UseUse',
            'variable' => '\PhpParser\Node\Expr\Variable',
            'while' => '\PhpParser\Node\Stmt\While_',
            'yield' => '\PhpParser\Node\Expr\Yield_',
            'yieldFrom' => '\PhpParser\Node\Expr\YieldFrom',
        ];
        
        if (!$class) {
            return $map;
        }

        if (isset($map[$class])) {
            return $map[$class];
        }

        return null;
    }

    public function propertyMap($property)
    {
        // Collisions:
        // 0 => "method",
        // 58 => "class",
        // 66 => "else",
        // 68 => "finally",
        // 76 => "if",
        // 86 => "static",
        // 91 => "trait",
        
        // TEMPORARY FIX WITH NAMESPACING $1___
        // CONSIDER USING A MAGIC GETTER TO ALLOW SAME NAME AS METHOD?

        $map = [
            'expr' => 'expr',
            'attributes' => 'attributes',
            'type' => 'type',
            'name' => 'name',
            'alias' => 'alias',
            'vars' => 'vars',
            'stmts' => 'stmts',
            'traits' => 'traits',
            'adaptations' => 'adaptations',
            'insteadof' => 'insteadof',
            'trait___' => 'trait',
            'method___' => 'method',
            'newModifier' => 'newModifier',
            'newName' => 'newName',
            'types' => 'types',
            'var' => 'var',
            'flags' => 'flags',
            'extends' => 'extends',
            'implements' => 'implements',
            'default' => 'default',
            'cond' => 'cond',
            'num' => 'num',
            'byRef' => 'byRef',
            'params' => 'params',
            'returnType' => 'returnType',
            'magicNames' => 'magicNames',
            'remaining' => 'remaining',
            'key' => 'key',
            'value' => 'value',
            'catches' => 'catches',
            'finally___' => 'finally',
            'exprs' => 'exprs',
            'declares' => 'declares',
            'props' => 'props',
            'elseifs' => 'elseifs',
            'else___' => 'else',
            'consts' => 'consts',
            'cases' => 'cases',
            'keyVar' => 'keyVar',
            'valueVar' => 'valueVar',
            'init' => 'init',
            'loop' => 'loop',
            'prefix' => 'prefix',
            'use' => 'use',
            'uses' => 'uses',
            'specialClassNames' => 'specialClassNames',
            'variadic' => 'variadic',
            'left' => 'left',
            'right' => 'right',
            'items' => 'items',
            'parts' => 'parts',
            'class___' => 'class',
            'args' => 'args',
            'static___' => 'static',
            'dim' => 'dim',
            'if___' => 'if',
            'unpack' => 'unpack',
            'replacements' => 'replacements',
        ];

        if (!$property) {
            return $map;
        }

        if (isset($map[$property])) {
            return $map[$property];
        }

        return null;
    }
}

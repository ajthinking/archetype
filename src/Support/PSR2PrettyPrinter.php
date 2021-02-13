<?php

namespace Archetype\Support;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\PrettyPrinter\Standard as StandardPrettyPrinter;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Expr\Array_;

class PSR2PrettyPrinter extends StandardPrettyPrinter
{

    public function __construct($options = [])
    {
        $defaults = ['shortArraySyntax' => true];

        parent::__construct($options + $defaults);
    }

    // Fix empty line before class definition
    protected function pStmt_Class(Class_ $node)
    {
        return PHP_EOL . $this->pClassCommon($node, ' ' . $node->name); // $this->pStmts($node->stmts)
    }

    // Fix empty line before class definition
    protected function pStmt_ClassMethod(ClassMethod $node)
    {
        $comments = $node->getComments();
        


        $ln = $comments ? '' : $this->nl;

        return $ln . $this->pAttrGroups($node->attrGroups)
             . $this->pModifiers($node->flags)
             . 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pMaybeMultiline($node->params) . ')'
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . (null !== $node->stmts
                ? $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}'
                : ';');
    }

    protected function pExpr_Array(Array_ $node)
    {
        // Fix proper multiline here
        return parent::pExpr_array($node);
    }

    /**
     * Ensure spacing between class stmts
     *
     * @param [type] $nodes
     * @return void
     */
    protected function pClassCommon(Class_ $node, $afterClassToken)
    {
        return $this->pModifiers($node->flags)
        . 'class' . $afterClassToken
        . (null !== $node->extends ? ' extends ' . $this->p($node->extends) : '')
        . (!empty($node->implements) ? ' implements ' . $this->pCommaSeparated($node->implements) : '')
        . $this->nl . '{' . $this->pSeparatedStmts($node->stmts) . $this->nl . '}';
    }

    protected function implementsSeparated($nodes)
    {
        if (count($nodes) > 1) {
            return "\n    ".$this->pImplode($nodes, ",\n    ");
        } else {
            return ' '.$this->pImplode($nodes, ', ');
        }
    }

    /**
     * Add linebreaks between stmts
     *
     * @param array $nodes
     * @param boolean $indent
     * @return string
     */
    protected function pSeparatedStmts(array $nodes, bool $indent = true) : string
    {

        if ($indent) {
            $this->indent();
        }

        $result = '';

        foreach ($nodes as $index => $node) {
            $comments = $node->getComments();
            if ($comments) {
                $result .= $this->nl . $this->pComments($comments);
                if ($node instanceof Nop) {
                    continue;
                }
            }

            $result .= $this->nl . $this->p($node);

            if ($index < count($nodes) - 1) {
                $result .= $this->nl;
            }
        }

        if ($indent) {
            $this->outdent();
        }

        return $result;
    }
    
    protected function pAttrGroups(array $nodes, bool $inline = false): string
    {
        $result = '';
        $sep = $inline ? ' ' : $this->nl;
        foreach ($nodes as $node) {
            $result .= $this->p($node) . $sep;
        }

        return $result;
    }
    
    protected function pMaybeMultiline(array $nodes, bool $trailingComma = false)
    {
        if (!$this->hasNodeWithComments($nodes)) {
            return $this->pCommaSeparated($nodes);
        } else {
            return $this->pCommaSeparatedMultiline($nodes, $trailingComma) . $this->nl;
        }
    }
    
    /**
     * @param Node[] $nodes
     * @return bool
     */
    protected function hasNodeWithComments(array $nodes)
    {
        foreach ($nodes as $node) {
            if ($node && $node->getComments()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Prints reformatted text of the passed comments.
     *
     * @param Comment[] $comments List of comments
     *
     * @return string Reformatted text of comments
     */
    protected function pComments(array $comments) : string
    {
        return $this->nl . parent::pComments($comments);
    }
}

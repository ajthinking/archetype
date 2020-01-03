<?php

namespace Ajthinking\PHPFileManipulator;

use PhpParser\Node\Stmt\Class_;
use PhpParser\PrettyPrinter\Standard as StandardPrettyPrinter;
use PhpParser\Node\Stmt\Nop;

class PSR2PrettyPrinter extends StandardPrettyPrinter {
    // Fix empty line before class definition
    protected function pStmt_Class(Class_ $node) {
        return PHP_EOL . $this->pClassCommon($node, ' ' . $node->name); // $this->pStmts($node->stmts)
    }

    protected function pClassCommon(Class_ $node, $afterClassToken) {
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

    protected function pSeparatedStmts(array $nodes, bool $indent = true) : string {
        if ($indent) {
            $this->indent();
        }

        $result = '';

        foreach ($nodes as $index=>$node) {
            $comments = $node->getComments();
            if ($comments) {
                $result .= $this->nl . $this->pComments($comments);
                if ($node instanceof Nop) {
                    continue;
                }
            }

            $result .= $this->nl . $this->p($node);

            if($index < count($nodes) - 1) {
                $result .= $this->nl;    
            }
        }

        if ($indent) {
            $this->outdent();
        }

        return $result;
    }    
}
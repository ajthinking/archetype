<?php

namespace Archetype\Schema\SimpleSchema\Directives;

class FloatDirective // extends BaseDirective
{
    public int $precision;

    public int $scale;

    public function __construct(int $precision = 8, int $scale = 2)
    {
        $this->precision = $precision;
        $this->scale = $scale;
    }

    public function handle()
    {
        // DOES WHAT THE DIRECTIVE DOES
    }
}

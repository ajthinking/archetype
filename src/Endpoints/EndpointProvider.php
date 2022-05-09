<?php

namespace Archetype\Endpoints;

use Archetype\PHPFile;
use Archetype\Traits\HasDirectiveHandlers;

abstract class EndpointProvider
{
    use HasDirectiveHandlers;

	public $file;

    protected $directives;
    
    public function __construct(PHPFile $file = null)
    {
        $this->file = $file;

        // proxy directives
        $this->directives = $this->file ? $this->file->directives() : [];
    }

    protected function ast()
    {
        return $this->file->ast();
    }

    protected function canUseReflection(): bool
    {
        return $this->file->getReflection() && !$this->file->hasModifications();
    }
}

<?php

namespace Archetype\Generators;

use Archetype\Generators\BaseGenerator;
use Archetype\Schema\SimpleSchema\SimpleSchema;

class Migration extends BaseGenerator
{
    public function qualifies()
    {
        return false;
    }
        
    public function build()
    {
    }
}

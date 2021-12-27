<?php

namespace Archetype\Endpoints;

use Archetype\Endpoints\EndpointProvider;

class SyntacticSweetener extends EndpointProvider
{
    protected $words = [
        'a',
        'also',
        'and',
        'epic',
        'from',
        'have',
        'it',
        'make',
        'please',
        'should',
        'thanks',
        'then',
        'to',
    ];

    public function __call($method, $args)
    {
        return $this->file;
    }

    protected function getHandlerMethod($signature, $args)
    {
        return collect($this->words)->contains($signature) ? $signature : false;
    }

    public function getEndpoints()
    {
        return $this->words;
    }
}

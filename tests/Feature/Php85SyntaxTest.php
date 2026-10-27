<?php

use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;

// Archetype parses with php-parser's newest supported version rather than the
// host's, so a file using PHP 8.5 syntax must survive a load/edit/render round
// trip whichever PHP runs the tests.
$php85 = <<<'PHP'
<?php

namespace App;

#[\Attribute]
final class Pipeline
{
    #[\Deprecated]
    public const string NAME = 'pipeline';

    public function __construct(final public string $input)
    {
    }

    #[\NoDiscard]
    public function slug(): string
    {
        return $this->input |> trim(...) |> strtolower(...);
    }

    public function run(): void
    {
        (void) $this->slug();
    }
}
PHP;

it('can load and edit a file using php 8.5 syntax', function() use ($php85) {
	$output = PHPFile::fromString($php85)
		->className('RenamedPipeline')
		->add()->property('extra', 1)
		->assertValidPhp()
		->render();

	expect($output)
		->toContain('class RenamedPipeline')
		->toContain('|> trim(...) |> strtolower(...)')
		->toContain('(void) $this->slug()')
		->toContain('final public string $input')
		->toContain('#[\NoDiscard]');
});

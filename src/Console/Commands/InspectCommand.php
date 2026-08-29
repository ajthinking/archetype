<?php

namespace Archetype\Console\Commands;

use Archetype\Console\Support\Introspector;
use Archetype\Console\Support\Target;
use Archetype\Console\TargetedCommand;
use Archetype\Facades\LaravelFile;
use Archetype\PHPFile;
use InvalidArgumentException;

/**
 * A structural summary of a file: what it is, what it has, what it does not.
 *
 * Method bodies are deliberately left out — `archetype:show` prints those — so
 * this stays a description of shape rather than a second copy of the file.
 */
class InspectCommand extends TargetedCommand
{
    const SECTIONS = ['meta', 'traits', 'uses', 'consts', 'cases', 'props', 'methods', 'relations'];

    protected $signature = 'archetype:inspect
        {target : '.self::TARGET_DESCRIPTION.'}
        {sections?* : Limit the summary to meta, traits, uses, consts, cases, props, methods or relations}';

    protected $description = 'Summarise the structure of a PHP or Laravel file';

    protected function perform(): int
    {
        $sections = $this->sections();
        $files = [];

        foreach ($this->targets() as $path) {
            $files[] = $this->describe($path, LaravelFile::load($path), $sections);
        }

        // A directory target always answers with a collection, even when it
        // matched one file, so the shape is a property of the question rather
        // than of the answer.
        $this->payload = Target::isDirectory($this->argument('target'))
            ? ['files' => $files, 'count' => count($files)]
            : $files[0];

        return self::SUCCESS;
    }

    /** @return array<int, string> */
    protected function sections(): array
    {
        $sections = $this->argument('sections') ?: self::SECTIONS;

        foreach ($sections as $section) {
            if (! in_array($section, self::SECTIONS, true)) {
                throw new InvalidArgumentException(
                    "unknown section '$section' — one of ".implode(', ', self::SECTIONS)
                );
            }
        }

        return $sections;
    }

    /** @return array<string, mixed> */
    protected function describe(string $path, PHPFile $file, array $sections): array
    {
        $scope = new Introspector($file);
        $data = ['file' => $path];

        $this->emit($path);

        if (in_array('meta', $sections, true)) {
            $data += [
                'kind' => $scope->kind(),
                'namespace' => (string) $file->namespace(),
                'name' => $scope->name(),
                'extends' => $scope->extends(),
                'implements' => $scope->implements(),
            ];

            $this->emit(trim(sprintf(
                '%s %s%s%s',
                $data['kind'],
                $data['namespace'] ? $data['namespace'].'\\'.$data['name'] : $data['name'],
                $data['extends'] ? ' extends '.implode(', ', $data['extends']) : '',
                $data['implements'] ? ' implements '.implode(', ', $data['implements']) : ''
            )));
        }

        if (in_array('traits', $sections, true)) {
            $data['traits'] = $file->useTrait();

            if ($data['traits']) {
                $this->emit('uses '.implode(', ', $data['traits']));
            }
        }

        if (in_array('uses', $sections, true)) {
            $data['imports'] = $file->use();

            foreach ($data['imports'] as $import) {
                $this->emit("import $import");
            }
        }

        if (in_array('consts', $sections, true)) {
            $data['constants'] = $scope->constants();

            foreach ($data['constants'] as $constant) {
                $this->emit('const '.$constant['name'].' = '.$this->literal($constant));
            }
        }

        if (in_array('cases', $sections, true)) {
            $data['cases'] = $scope->cases();

            foreach ($data['cases'] as $case) {
                $this->emit(trim('case '.$case['name'].($case['value'] === null ? '' : ' = '.$this->literal($case))));
            }
        }

        if (in_array('props', $sections, true)) {
            $data['properties'] = $scope->properties();

            foreach ($data['properties'] as $property) {
                $this->emit(sprintf(
                    'prop %s%s $%s = %s',
                    $property['visibility'],
                    $property['static'] ? ' static' : '',
                    $property['name'],
                    $this->literal($property)
                ));
            }
        }

        if (in_array('methods', $sections, true)) {
            $data['methods'] = $scope->methods();

            foreach ($data['methods'] as $method) {
                $this->emit(sprintf(
                    'fn %s%s %s(%s)%s [%d lines]',
                    $method['visibility'],
                    $method['static'] ? ' static' : '',
                    $method['name'],
                    $method['params'],
                    $method['returns'] ? ': '.$method['returns'] : '',
                    $method['lines']
                ));
            }
        }

        if (in_array('relations', $sections, true)) {
            $data['relations'] = $scope->relations();

            foreach ($data['relations'] as $relation) {
                $this->emit(sprintf('rel %s %s %s', $relation['name'], $relation['type'], $relation['target'] ?? '?'));
            }
        }

        return $data;
    }

    /** `?` rather than a wrong value when the declaration is not a constant expression. */
    protected function literal(array $entry): string
    {
        return $entry['evaluated'] ? json_encode($entry['value'], JSON_UNESCAPED_SLASHES) : '?';
    }
}

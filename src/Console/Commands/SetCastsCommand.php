<?php

namespace Archetype\Console\Commands;

use Archetype\Console\MutationCommand;
use Archetype\Console\Support\ArrayLiteral;
use Archetype\Console\Support\Code;
use Archetype\Console\Support\Introspector;
use Archetype\LaravelFile;
use InvalidArgumentException;
use PhpParser\Node;

/**
 * Set entries on a model's attribute casts.
 *
 * Laravel 11 generates `protected function casts(): array` while older models
 * declare `protected $casts`, and `getCasts()` merges both — so writing to the
 * wrong one is functionally correct and structurally wrong. This writes to
 * whichever the model already uses, and only falls back to creating the
 * property when it uses neither.
 */
class SetCastsCommand extends MutationCommand
{
    protected $signature = 'archetype:set-casts
        {target : '.self::TARGET_DESCRIPTION.'}
        {casts* : field=cast pairs, where the cast is any PHP expression — datetime, ProjectStatus::class}';

    protected $description = 'Set attribute casts on an Eloquent model';

    protected function perform(): int
    {
        $casts = $this->casts();

        return $this->mutate(function (LaravelFile $file) use ($casts) {
            $this->requireKind($file, ['class']);

            [$array, $where] = $this->literal($file);

            $counts = ['added' => 0, 'updated' => 0, 'unchanged' => 0];

            foreach ($casts as $field => $cast) {
                $counts[ArrayLiteral::set($array, $field, $cast)]++;
            }

            if ($counts['added'] === 0 && $counts['updated'] === 0) {
                return $this->unchanged('casts unchanged');
            }

            return sprintf(
                'casts +%d ~%d in %s',
                $counts['added'],
                $counts['updated'],
                $where
            );
        });
    }

    /** @return array<string, Node\Expr> */
    protected function casts(): array
    {
        $casts = [];

        foreach ($this->argument('casts') as $pair) {
            if (! str_contains($pair, '=')) {
                throw new InvalidArgumentException("expected field=cast, got '$pair'");
            }

            [$field, $cast] = explode('=', $pair, 2);

            $casts[$field] = Code::literal($cast);
        }

        return $casts;
    }

    /**
     * Find the array the model actually casts through, creating one if needed.
     *
     * @return array{0: Node\Expr\Array_, 1: string}
     */
    protected function literal(LaravelFile $file): array
    {
        if ((new Introspector($file))->method('casts')) {
            $array = ArrayLiteral::returnedBy($file, 'casts');

            if (! $array) {
                throw new InvalidArgumentException('casts() does not return an array literal directly');
            }

            return [$array, 'casts()'];
        }

        if ($array = ArrayLiteral::defaultOf($file, 'casts')) {
            return [$array, '$casts'];
        }

        $file->assumeType('array')->protected()->property('casts', []);

        $array = ArrayLiteral::defaultOf($file, 'casts');

        if (! $array) {
            throw new \RuntimeException('could not create a $casts property');
        }

        return [$array, '$casts'];
    }
}

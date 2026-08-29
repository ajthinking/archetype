<?php

namespace Archetype\Console\Support;

/**
 * A small unified diff, used to make a mutation self-verifying.
 *
 * A caller that can see what changed has no reason to re-read the file, which
 * is the point: the mutation's own answer replaces the verification read.
 * Output is capped, because a diff longer than the file defeats that.
 */
class Diff
{
    public function __construct(
        protected int $context = 1,
        protected int $maxLines = 16,
    ) {
    }

    public function render(string $before, string $after): string
    {
        if ($before === $after) {
            return '';
        }

        $hunks = $this->hunks(explode("\n", $before), explode("\n", $after));

        if (! $hunks) {
            return '';
        }

        $out = [];
        $budget = $this->maxLines;

        foreach ($hunks as $hunk) {
            if ($budget <= 0) {
                $out[] = '  … more changes not shown';
                break;
            }

            $out[] = sprintf('@@ %d @@', $hunk['line']);

            foreach ($hunk['lines'] as $line) {
                if ($budget-- <= 0) {
                    $out[] = '  …';
                    break;
                }

                $out[] = $line;
            }
        }

        return implode("\n", $out);
    }

    /** @return array<int, array{line:int, lines:array<int,string>}> */
    protected function hunks(array $a, array $b): array
    {
        $hunks = [];
        $current = null;
        $gap = 0;

        foreach ($this->ops($a, $b) as [$kind, $line, $index]) {
            if ($kind === ' ') {
                if ($current === null) {
                    continue;
                }

                if (++$gap > $this->context) {
                    $hunks[] = $current;
                    $current = null;
                    $gap = 0;

                    continue;
                }

                $current['lines'][] = '  '.$line;

                continue;
            }

            if ($current === null) {
                $current = ['line' => $index + 1, 'lines' => []];
            }

            $gap = 0;
            $current['lines'][] = $kind.' '.$line;
        }

        if ($current !== null) {
            $hunks[] = $current;
        }

        return $hunks;
    }

    /**
     * Classic LCS diff. The inputs are single PHP classes, so the quadratic
     * table is a few thousand cells at worst.
     *
     * @return array<int, array{0:string,1:string,2:int}>
     */
    protected function ops(array $a, array $b): array
    {
        $n = count($a);
        $m = count($b);

        $lcs = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

        for ($i = $n - 1; $i >= 0; $i--) {
            for ($j = $m - 1; $j >= 0; $j--) {
                $lcs[$i][$j] = $a[$i] === $b[$j]
                    ? $lcs[$i + 1][$j + 1] + 1
                    : max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
            }
        }

        $ops = [];
        $i = $j = 0;

        while ($i < $n && $j < $m) {
            if ($a[$i] === $b[$j]) {
                $ops[] = [' ', $a[$i], $i];
                $i++;
                $j++;
            } elseif ($lcs[$i + 1][$j] >= $lcs[$i][$j + 1]) {
                $ops[] = ['-', $a[$i], $i];
                $i++;
            } else {
                $ops[] = ['+', $b[$j], $i];
                $j++;
            }
        }

        while ($i < $n) {
            $ops[] = ['-', $a[$i], $i];
            $i++;
        }

        while ($j < $m) {
            $ops[] = ['+', $b[$j], $i];
            $j++;
        }

        return $ops;
    }
}

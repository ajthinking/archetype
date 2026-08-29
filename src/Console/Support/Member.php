<?php

namespace Archetype\Console\Support;

use Archetype\PHPFile;
use PhpParser\Node;
use RuntimeException;

/**
 * Adds a member to whatever class-like a file declares.
 *
 * The library's own inserter puts a new statement in front of the ones of its
 * kind, which reads oddly for generated code — a new method turns up above
 * everything already written. The console appends instead, after the last
 * member of the same kind and before the kinds that conventionally follow it.
 *
 * Using ClassLike rather than Class_ is the other half: an enum, interface or
 * trait is a first-class construct, and a mutation that silently matches
 * nothing on one is worse than a mutation that refuses.
 */
class Member
{
    /** Ascending: the order these kinds conventionally appear in a class body. */
    const ORDER = [
        Node\Stmt\TraitUse::class,
        Node\Stmt\ClassConst::class,
        Node\Stmt\EnumCase::class,
        Node\Stmt\Property::class,
        Node\Stmt\ClassMethod::class,
    ];

    public static function add(PHPFile $file, Node\Stmt $member): void
    {
        $classLike = (new Introspector($file))->classLike();

        if (! $classLike) {
            throw new RuntimeException('the file does not declare a class, enum, interface or trait');
        }

        $rank = static::rank($member);
        $position = 0;

        foreach ($classLike->stmts as $index => $statement) {
            if (static::rank($statement) <= $rank) {
                $position = $index + 1;
            }
        }

        array_splice($classLike->stmts, $position, 0, [$member]);
    }

    protected static function rank(Node\Stmt $node): int
    {
        $rank = array_search(get_class($node), self::ORDER, true);

        return $rank === false ? count(self::ORDER) : $rank;
    }
}

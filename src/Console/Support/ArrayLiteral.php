<?php

namespace Archetype\Console\Support;

use Archetype\PHPFile;
use Archetype\Support\AST\Visitors\FormattingRemover;
use Archetype\Support\PSR2PrettyPrinter;
use PhpParser\Node;
use PhpParser\NodeFinder;

/**
 * Reading and writing the array literal a method returns.
 *
 * Laravel keeps a great deal of configuration in exactly this shape —
 * `rules()`, `toArray()`, `casts()`, `definition()` — and it is the one place
 * Archetype's property endpoints cannot reach, because the payload lives inside
 * a method body rather than beside it.
 *
 * The rule for which array is meant is deliberately shallow: the array returned
 * by the method itself, never one returned from a closure nested inside it.
 */
class ArrayLiteral
{
    /** The array literal returned by `$method`, or null if it does not return one directly. */
    public static function returnedBy(PHPFile $file, string $method): ?Node\Expr\Array_
    {
        $node = (new Introspector($file))->method($method);

        if (! $node) {
            return null;
        }

        foreach ($node->stmts ?? [] as $statement) {
            if ($statement instanceof Node\Stmt\Return_ && $statement->expr instanceof Node\Expr\Array_) {
                return $statement->expr;
            }
        }

        return static::nestedReturn($node);
    }

    /** The array literal a property is initialised with, or null when it is not an array. */
    public static function defaultOf(PHPFile $file, string $property): ?Node\Expr\Array_
    {
        foreach ((new NodeFinder)->findInstanceOf($file->ast(), Node\Stmt\Property::class) as $node) {
            foreach ($node->props as $prop) {
                if ($prop->name->name === $property && $prop->default instanceof Node\Expr\Array_) {
                    return $prop->default;
                }
            }
        }

        return null;
    }

    /**
     * Set `$key` to `$value`, appending when the key is absent.
     *
     * @return string one of added|updated|unchanged
     */
    public static function set(Node\Expr\Array_ $array, string $key, Node\Expr $value): string
    {
        foreach ($array->items as $item) {
            if ($item instanceof Node\ArrayItem && static::keyOf($item) === $key) {
                if (static::print($item->value) === static::print($value)) {
                    return 'unchanged';
                }

                $item->value = $value;

                return 'updated';
            }
        }

        static::keepMultiline($array);

        $array->items[] = new Node\ArrayItem($value, new Node\Scalar\String_($key));

        return 'added';
    }

    /** Append a value with no key. Returns false when an identical value is already present. */
    public static function append(Node\Expr\Array_ $array, Node\Expr $value): bool
    {
        foreach ($array->items as $item) {
            if ($item instanceof Node\ArrayItem && $item->key === null && static::print($item->value) === static::print($value)) {
                return false;
            }
        }

        static::keepMultiline($array);

        $array->items[] = new Node\ArrayItem($value);

        return true;
    }

    /**
     * Keep a one-per-line array one-per-line.
     *
     * php-parser only calls a list multiline when it can see a newline between
     * two items, so an array holding a single item gets the new one appended on
     * the same line. Dropping the node's formatting makes the printer lay the
     * whole array out again in the style the rest of the file uses.
     */
    protected static function keepMultiline(Node\Expr\Array_ $array): void
    {
        $spansLines = $array->getStartLine() !== $array->getEndLine();

        if ($spansLines && count($array->items) < 2) {
            FormattingRemover::on($array);
        }
    }

    public static function remove(Node\Expr\Array_ $array, string $key): bool
    {
        $kept = array_values(array_filter(
            $array->items,
            fn ($item) => ! ($item instanceof Node\ArrayItem && static::keyOf($item) === $key)
        ));

        if (count($kept) === count($array->items)) {
            return false;
        }

        $array->items = $kept;

        return true;
    }

    public static function keyOf(Node\ArrayItem $item): ?string
    {
        return $item->key instanceof Node\Scalar\String_ ? $item->key->value : null;
    }

    public static function print(Node\Expr $node): string
    {
        return (new PSR2PrettyPrinter)->prettyPrintExpr($node);
    }

    /**
     * A `return [...]` somewhere inside the method but not inside a closure —
     * an early return in a conditional, typically.
     */
    protected static function nestedReturn(Node\Stmt\ClassMethod $method): ?Node\Expr\Array_
    {
        $finder = new NodeFinder;

        $closures = collect($finder->find($method->stmts ?? [], fn (Node $node) => $node instanceof Node\Expr\Closure
            || $node instanceof Node\Expr\ArrowFunction
            || $node instanceof Node\Stmt\Function_));

        foreach ($finder->findInstanceOf($method->stmts ?? [], Node\Stmt\Return_::class) as $return) {
            if (! $return->expr instanceof Node\Expr\Array_) {
                continue;
            }

            $nested = $closures->contains(fn (Node $closure) => $return->getStartLine() >= $closure->getStartLine()
                && $return->getEndLine() <= $closure->getEndLine());

            if (! $nested) {
                return $return->expr;
            }
        }

        return null;
    }
}

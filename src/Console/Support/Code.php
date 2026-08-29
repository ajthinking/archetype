<?php

namespace Archetype\Console\Support;

use Archetype\PHPFile;
use Archetype\Support\AST\Visitors\FormattingRemover;
use InvalidArgumentException;
use PhpParser\Error as ParserError;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

/**
 * Turning strings the caller typed into AST nodes, and AST nodes back into the
 * source the caller wrote.
 */
class Code
{
    /** Parse a method declaration given as `--code='public function x() { … }'`. */
    public static function method(string $code): Node\Stmt\ClassMethod
    {
        $method = (new NodeFinder)->findFirstInstanceOf(
            static::parse('class __ArchetypeScratch {'.PHP_EOL.static::stripTag($code).PHP_EOL.'}'),
            Node\Stmt\ClassMethod::class
        );

        if (! $method) {
            throw new InvalidArgumentException('could not parse a method declaration from the given code');
        }

        return FormattingRemover::on($method);
    }

    /**
     * Parse a value given on the command line as a PHP expression.
     *
     * This is what lets a caller write `'nullable|max:255'`, `['required']`,
     * `$this->budget_cents` or `Status::Active` in the same argument slot —
     * anything PHP itself accepts on the right of an assignment.
     */
    public static function expression(string $value): Node\Expr
    {
        $statements = static::parse('$__archetype = '.static::stripTag($value).';');
        $expression = $statements[0] ?? null;

        if (! $expression instanceof Node\Stmt\Expression || ! $expression->expr instanceof Node\Expr\Assign) {
            throw new InvalidArgumentException("could not parse '$value' as a PHP expression");
        }

        return FormattingRemover::on($expression->expr->expr);
    }

    /**
     * Parse a value the way a caller most likely meant it.
     *
     * `expression()` alone is not usable from a command line: `nullable|date`
     * is a perfectly valid PHP expression — a bitwise or of two constants — and
     * that is never what someone typing a validation rule meant. So a bare word
     * is a string, and PHP is only assumed where the text announces it: a
     * bracket, a quote, a variable, a call, a class constant, a number or a
     * boolean.
     */
    public static function literal(string $value): Node\Expr
    {
        return static::looksLikePhp($value)
            ? static::expression($value)
            : new Node\Scalar\String_($value);
    }

    protected static function looksLikePhp(string $value): bool
    {
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        return (bool) preg_match('/^[\[\(\\\\\'"$\-]/', $value)
            || is_numeric($value)
            || in_array(strtolower($value), ['true', 'false', 'null'], true)
            || (bool) preg_match('/^[A-Za-z_\\\\][A-Za-z0-9_\\\\]*\s*(::|\()/', $value);
    }

    /**
     * Decode a value as JSON when it is valid JSON, otherwise keep the string.
     *
     * Used where the endpoint wants a PHP value rather than an AST node, so
     * `'["a","b"]'` sets an array and `gdpr_users` sets a string.
     */
    public static function value(?string $raw)
    {
        if ($raw === null) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
    }

    /**
     * A fresh node per file.
     *
     * Inserting one node object into several ASTs would alias them, so a
     * directory-wide mutation must hand each file its own copy.
     */
    public static function copy(Node $node): Node
    {
        return FormattingRemover::on(unserialize(serialize($node)));
    }

    /** The source of one method, exactly as written, doc block included. */
    public static function source(PHPFile $file, Node\Stmt\ClassMethod $method): string
    {
        $lines = explode("\n", $file->contents());

        $comments = $method->getComments();
        $start = $comments ? $comments[0]->getStartLine() : $method->getStartLine();

        return implode("\n", array_slice($lines, $start - 1, $method->getEndLine() - $start + 1));
    }

    /** @return array<int, Node\Stmt> */
    protected static function parse(string $code): array
    {
        try {
            return (new ParserFactory)->createForNewestSupportedVersion()->parse('<?php '.$code) ?? [];
        } catch (ParserError $error) {
            throw new InvalidArgumentException('could not parse the given code: '.$error->getRawMessage());
        }
    }

    protected static function stripTag(string $code): string
    {
        return preg_replace('/^<\?php\s*/', '', trim($code));
    }
}

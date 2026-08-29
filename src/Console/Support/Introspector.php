<?php

namespace Archetype\Console\Support;

use Archetype\PHPFile;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node;
use PhpParser\NodeFinder;

/**
 * Read-only structural facts about a loaded PHPFile.
 *
 * Everything here reads the AST Archetype already exposes through
 * PHPFile::ast(). It exists because the console needs a few descriptions the
 * public endpoints do not surface — method signatures, enum cases and Eloquent
 * relationships — and because `inspect` must describe enums, interfaces and
 * traits, not only classes.
 */
class Introspector
{
    const RELATION_METHODS = [
        'hasMany', 'hasOne', 'belongsTo', 'belongsToMany',
        'morphMany', 'morphOne', 'morphTo', 'morphToMany', 'morphedByMany',
        'hasManyThrough', 'hasOneThrough',
    ];

    public function __construct(protected PHPFile $file)
    {
    }

    /** @return array<int, array{name:string,visibility:string,static:bool,abstract:bool,params:string,returns:?string,lines:int}> */
    public function methods(): array
    {
        return collect($this->find(Node\Stmt\ClassMethod::class))
            ->map(fn (Node\Stmt\ClassMethod $method) => [
                'name' => $method->name->name,
                'visibility' => $this->visibility($method),
                'static' => $method->isStatic(),
                'abstract' => $method->isAbstract(),
                'params' => collect($method->params)->map(fn ($p) => $this->param($p))->join(', '),
                'returns' => $method->returnType ? $this->type($method->returnType) : null,
                'lines' => $method->getEndLine() - $method->getStartLine() + 1,
            ])->values()->all();
    }

    public function method(string $name): ?Node\Stmt\ClassMethod
    {
        foreach ($this->find(Node\Stmt\ClassMethod::class) as $method) {
            if ($method->name->name === $name) {
                return $method;
            }
        }

        return null;
    }

    /** @return array<int, array{name:string,visibility:string,static:bool,value:mixed,evaluated:bool}> */
    public function properties(): array
    {
        $out = [];

        foreach ($this->find(Node\Stmt\Property::class) as $property) {
            foreach ($property->props as $prop) {
                [$value, $evaluated] = $this->evaluate($prop->default);

                $out[] = [
                    'name' => $prop->name->name,
                    'visibility' => $this->visibility($property),
                    'static' => $property->isStatic(),
                    'value' => $value,
                    'evaluated' => $evaluated,
                ];
            }
        }

        return $out;
    }

    public function hasProperty(string $name): bool
    {
        return collect($this->properties())->contains(fn ($property) => $property['name'] === $name);
    }

    /** @return array<int, array{name:string,type:string,target:?string}> */
    public function relations(): array
    {
        $out = [];

        foreach ($this->find(Node\Stmt\ClassMethod::class) as $method) {
            $calls = (new NodeFinder)->find($method->stmts ?? [], function (Node $node) {
                return $node instanceof Node\Expr\MethodCall
                    && $node->var instanceof Node\Expr\Variable
                    && $node->var->name === 'this'
                    && $node->name instanceof Node\Identifier
                    && in_array($node->name->name, self::RELATION_METHODS, true);
            });

            foreach ($calls as $call) {
                $out[] = [
                    'name' => $method->name->name,
                    'type' => $call->name->name,
                    'target' => $this->firstArgClass($call),
                ];
            }
        }

        return $out;
    }

    /** @return array<int, array{name:string, value:mixed, evaluated:bool}> */
    public function constants(): array
    {
        $out = [];

        foreach ($this->find(Node\Stmt\ClassConst::class) as $const) {
            foreach ($const->consts as $one) {
                [$value, $evaluated] = $this->evaluate($one->value);

                $out[] = ['name' => $one->name->name, 'value' => $value, 'evaluated' => $evaluated];
            }
        }

        return $out;
    }

    /** @return array<int, array{name:string, value:mixed, evaluated:bool}> Enum cases, when the file declares an enum. */
    public function cases(): array
    {
        $out = [];

        foreach ($this->find(Node\Stmt\EnumCase::class) as $case) {
            [$value, $evaluated] = $this->evaluate($case->expr);

            $out[] = [
                'name' => $case->name->name,
                'value' => $case->expr ? $value : null,
                'evaluated' => $case->expr ? $evaluated : true,
            ];
        }

        return $out;
    }

    public function hasCase(string $name): bool
    {
        return collect($this->cases())->contains(fn ($case) => $case['name'] === $name);
    }

    /** The declared class/enum/interface/trait name, whatever the construct. */
    public function name(): ?string
    {
        $node = $this->classLike();

        return $node && $node->name ? $node->name->name : null;
    }

    public function classLike(): ?Node\Stmt\ClassLike
    {
        return (new NodeFinder)->findFirstInstanceOf($this->file->ast(), Node\Stmt\ClassLike::class);
    }

    public function kind(): string
    {
        return match (true) {
            $this->classLike() instanceof Node\Stmt\Enum_ => 'enum',
            $this->classLike() instanceof Node\Stmt\Interface_ => 'interface',
            $this->classLike() instanceof Node\Stmt\Trait_ => 'trait',
            $this->classLike() instanceof Node\Stmt\Class_ => 'class',
            default => 'file',
        };
    }

    /** Interfaces may extend several parents, so this is always a list. */
    public function extends(): array
    {
        $node = $this->classLike();

        if ($node instanceof Node\Stmt\Class_) {
            return $node->extends ? [$node->extends->toString()] : [];
        }

        if ($node instanceof Node\Stmt\Interface_) {
            return collect($node->extends)->map(fn (Node\Name $name) => $name->toString())->all();
        }

        return [];
    }

    public function implements(): array
    {
        $node = $this->classLike();

        $names = match (true) {
            $node instanceof Node\Stmt\Class_ => $node->implements,
            $node instanceof Node\Stmt\Enum_ => $node->implements,
            default => [],
        };

        return collect($names)->map(fn (Node\Name $name) => $name->toString())->all();
    }

    /** @return array<int, Node> */
    protected function find(string $class): array
    {
        return (new NodeFinder)->findInstanceOf($this->file->ast(), $class);
    }

    protected function visibility(Node\Stmt\ClassMethod|Node\Stmt\Property $node): string
    {
        return match (true) {
            $node->isPrivate() => 'private',
            $node->isProtected() => 'protected',
            default => 'public',
        };
    }

    protected function firstArgClass(Node\Expr\MethodCall $call): ?string
    {
        $arg = $call->args[0] ?? null;

        if (! $arg instanceof Node\Arg) {
            return null;
        }

        if ($arg->value instanceof Node\Expr\ClassConstFetch && $arg->value->class instanceof Node\Name) {
            return $arg->value->class->toString();
        }

        if ($arg->value instanceof Node\Scalar\String_) {
            return $arg->value->value;
        }

        return null;
    }

    protected function param(Node\Param $param): string
    {
        $type = $param->type ? $this->type($param->type).' ' : '';
        $name = $param->var instanceof Node\Expr\Variable ? '$'.$param->var->name : '$?';

        if ($param->default) {
            [$value, $evaluated] = $this->evaluate($param->default);
            $name .= ' = '.($evaluated ? json_encode($value) : '?');
        }

        return $type.$name;
    }

    protected function type($type): string
    {
        return match (true) {
            $type instanceof Node\NullableType => '?'.$this->type($type->type),
            $type instanceof Node\UnionType => collect($type->types)->map(fn ($t) => $this->type($t))->join('|'),
            $type instanceof Node\IntersectionType => collect($type->types)->map(fn ($t) => $this->type($t))->join('&'),
            $type instanceof Node\Name => $type->toString(),
            $type instanceof Node\Identifier => $type->name,
            default => 'mixed',
        };
    }

    /** @return array{0: mixed, 1: bool} the value, and whether it could be evaluated at all */
    protected function evaluate(?Node $node): array
    {
        if ($node === null) {
            return [null, true];
        }

        try {
            return [(new ConstExprEvaluator)->evaluateSilently($node), true];
        } catch (\Throwable) {
            // Not a constant expression. Reported as unknown rather than
            // pretending the declaration has no value.
            return [null, false];
        }
    }
}

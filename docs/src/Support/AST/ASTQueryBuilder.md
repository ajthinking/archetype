# ASTQueryBuilder

> Work in progress :warning: Only a few examples listed below. 

> Tip! Search for `->astQuery()` in package source code to see real internal use case examples

List of available traversing methods/properties: https://github.com/ajthinking/archetype/blob/master/src/Traits/PHPParserClassMap.php

```
// get a ASTQueryBuilder
$file->astQuery()
```

```
// dont allow deep search
$file->astQuery()->shallow()
```

```
// allow deep search (default)
$file->astQuery()->deep()
```

```
// remember and recall values in middle of query
file->astQuery()
    ->class()
    ->extends
    ->remember('formatted_extends', function($node) {
        $parts = $node->parts ?? null;
        return $parts ? join('\\', $parts) : null;
    })
    ->recall()
    ->pluck('formatted_extends')
    ->first();

```

```
// where property path
$file->astQuery()->method()->where('name->name', 'myMethod')
```

```
// where callback
$file->astQuery()->method()->where(function($query) {
    return true;
})
```

```
// sub query, useful for lookahead
$this->file->astQuery()
            ->class()
            ->property()
            ->where(function($query) use($key) {
                return $query->propertyProperty()
                    ->where('name->name', $key)
                    ->get()->isNotEmpty();
            })
```

```
// Remove node
$file->astQuery()
        ->class()
        ->property()
        ->where(...)
        ->remove()
```

````
// replace node
$file->astQuery()
            ->class()
            ->name
            ->replaceProperty('name', $newClassName)
```

```
// insert statements
$this->file->astQuery()
    ->class()
    ->insertStmt($method)
```

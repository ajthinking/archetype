# ASTQueryBuilder

> Work in progress :warning: Only a few examples listed below. 

List of available traversing methods/properties: https://github.com/ajthinking/archetype/blob/master/src/Traits/PHPParserClassMap.php

```php
// get a ASTQueryBuilder
$file->astQuery()
```

```php
// dont allow deep search
$file->astQuery()->shallow()
```

```php
// allow deep search (default)
$file->astQuery()->deep()
```

```php
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

```php
// where property path
$file->astQuery()->method()->where('name->name', 'myMethod')
```

```php
// where callback
$file->astQuery()->method()->where(function($query) {
    return true;
})
```

```php
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

```php
// Remove node
$file->astQuery()
        ->class()
        ->property()
        ->where(...)
        ->remove()
```

```php
// replace node
$file->astQuery()
            ->class()
            ->name
            ->replaceProperty('name', $newClassName)
```

```php
// insert statements
$this->file->astQuery()
    ->class()
    ->insertStmt($method)
```

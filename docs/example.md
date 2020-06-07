
## API

> Archetype\Endpoints\PHP\Namespace
```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```

> Archetype\Endpoints\PHP\ClassName
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```

> Archetype\Endpoints\PHP\AstQuery
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```

> Archetype\Endpoints\PHP\Extends
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
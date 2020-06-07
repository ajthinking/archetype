
## API

> Namespace
```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```

> ClassName
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```

> AstQuery
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```

> Extends
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
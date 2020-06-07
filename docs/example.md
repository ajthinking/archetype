
## API

### <b>Namespace_</b>

```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```
### <b>ClassName</b>
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```
### <b>AstQuery</b>
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```
### <b>Extends</b>
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
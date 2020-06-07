
## PHPFile API

<hr>

![abcdef](https://img.shields.io/badge/-namespace-green)

```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```


<hr>

![abcdef](https://img.shields.io/badge/-className-green)

```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```

<hr>

![abcdef](https://img.shields.io/badge/-astQuery-green)

```php
// Get a AstQueryBuilder instance
$file->astQuery()
```

<hr>

![abcdef](https://img.shields.io/badge/-extends-green)

```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```


## LaravelFile API

```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```

<hr>

```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```

<hr>

```php
// Get a AstQueryBuilder instance
$file->astQuery()
```

<hr>

```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
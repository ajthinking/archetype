<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Namespace_.php'>![Namespace_](https://img.shields.io/badge/-Namespace_-blue)
```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/MethodNames.php'>![MethodNames](https://img.shields.io/badge/-MethodNames-blue)
```php
// Get class method names
$file->methodNames()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/TraitUse.php'>![TraitUse](https://img.shields.io/badge/-TraitUse-blue)
```php
// Get class traits
$file->trait()

// Set class traits
INCOMPLETE

// Add class traits
INCOMPLETE
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Maker.php'>![Maker](https://img.shields.io/badge/-Maker-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/PHPFileQueryBuilder.php'>![PHPFileQueryBuilder](https://img.shields.io/badge/-PHPFileQueryBuilder-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/ReflectionProxy.php'>![ReflectionProxy](https://img.shields.io/badge/-ReflectionProxy-blue)
```php
// Get ReflectionClass
$file->getReflection()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/ClassName.php'>![ClassName](https://img.shields.io/badge/-ClassName-blue)
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Method.php'>![Method](https://img.shields.io/badge/-Method-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/AstQuery.php'>![AstQuery](https://img.shields.io/badge/-AstQuery-blue)
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Property.php'>![Property](https://img.shields.io/badge/-Property-blue)
```php
// Get class property
$file->property('table')

// Set class property
$file->property('table', 'gdpr_users')

// Remove class property
$file->remove()->property('table')

// Clear class property default value
$file->clear()->property('table')

// Empty class array property
$file->empty()->property('fillable')

// Add item to class array property
$file->add()->property('fillable', 'nickname')

// Append to class string property
$file->add()->property('table', '_gdpr')

// Add item to class array property
$file->add()->property('fillable', 'nickname')

// Explicitly set class property without default value
$file->setProperty('propertyWithoutDefaultValue')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Extends_.php'>![Extends_](https://img.shields.io/badge/-Extends_-blue)
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Maker/PHPTemplate.php'>![PHPTemplate](https://img.shields.io/badge/-PHPTemplate-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Use_.php'>![Use_](https://img.shields.io/badge/-Use_-blue)
```php
// Get file uses
$file->use()

// Set file uses
$file->use(['ClassA', 'classB'])

// Use with alias
$file->use('ClassA as Ajthinking')

// Add file uses
$file->add()->use('AdditionalClass')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Implements_.php'>![Implements_](https://img.shields.io/badge/-Implements_-blue)
```php
// Get class implements
$file->implements()

// Set class implements
$file->implements(['InterfaceA', 'InterfaceB'])

// Add class implements
$file->add()->implements('InterfaceC')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/SyntacticSweetener.php'>![SyntacticSweetener](https://img.shields.io/badge/-SyntacticSweetener-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/BelongsTo.php'>![BelongsTo](https://img.shields.io/badge/-BelongsTo-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/BelongsToMany.php'>![BelongsToMany](https://img.shields.io/badge/-BelongsToMany-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/ModelProperties.php'>![ModelProperties](https://img.shields.io/badge/-ModelProperties-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/LaravelFileQueryBuilder.php'>![LaravelFileQueryBuilder](https://img.shields.io/badge/-LaravelFileQueryBuilder-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/LaravelTemplate.php'>![LaravelTemplate](https://img.shields.io/badge/-LaravelTemplate-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/NamepspacedClass.php'>![NamepspacedClass](https://img.shields.io/badge/-NamepspacedClass-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Model.php'>![Model](https://img.shields.io/badge/-Model-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Unimplemented.php'>![Unimplemented](https://img.shields.io/badge/-Unimplemented-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Command.php'>![Command](https://img.shields.io/badge/-Command-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/HasMany.php'>![HasMany](https://img.shields.io/badge/-HasMany-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/HasOne.php'>![HasOne](https://img.shields.io/badge/-HasOne-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/LaravelMaker.php'>![LaravelMaker](https://img.shields.io/badge/-LaravelMaker-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/EndpointProvider.php'>![EndpointProvider](https://img.shields.io/badge/-EndpointProvider-blue)
```php
// UNDOCUMENTED CLASS
```
<hr>
# File read/write API

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Namespace_.php'>![Archetype\Endpoints\PHP\Namespace_](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Namespace_-blue)</a>
```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/MethodNames.php'>![Archetype\Endpoints\PHP\MethodNames](https://img.shields.io/badge/-Archetype\Endpoints\PHP\MethodNames-blue)</a>
```php
// Get class method names
$file->methodNames()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/TraitUse.php'>![Archetype\Endpoints\PHP\TraitUse](https://img.shields.io/badge/-Archetype\Endpoints\PHP\TraitUse-blue)</a>
```php
// Get class traits
$file->trait()

// Set class traits
INCOMPLETE

// Add class traits
INCOMPLETE
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Maker.php'>![Archetype\Endpoints\PHP\Maker](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Maker-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/PHPFileQueryBuilder.php'>![Archetype\Endpoints\PHP\PHPFileQueryBuilder](https://img.shields.io/badge/-Archetype\Endpoints\PHP\PHPFileQueryBuilder-blue)</a>
```php
// Custom code loaded from a md file
$files = PHPFile::where('className', 'like' '%Create%')->get();
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/ReflectionProxy.php'>![Archetype\Endpoints\PHP\ReflectionProxy](https://img.shields.io/badge/-Archetype\Endpoints\PHP\ReflectionProxy-blue)</a>
```php
// Get ReflectionClass
$file->getReflection()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/ClassName.php'>![Archetype\Endpoints\PHP\ClassName](https://img.shields.io/badge/-Archetype\Endpoints\PHP\ClassName-blue)</a>
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Method.php'>![Archetype\Endpoints\PHP\Method](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Method-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/AstQuery.php'>![Archetype\Endpoints\PHP\AstQuery](https://img.shields.io/badge/-Archetype\Endpoints\PHP\AstQuery-blue)</a>
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Property.php'>![Archetype\Endpoints\PHP\Property](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Property-blue)</a>
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

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Extends_.php'>![Archetype\Endpoints\PHP\Extends_](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Extends_-blue)</a>
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Use_.php'>![Archetype\Endpoints\PHP\Use_](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Use_-blue)</a>
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

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Implements_.php'>![Archetype\Endpoints\PHP\Implements_](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Implements_-blue)</a>
```php
// Get class implements
$file->implements()

// Set class implements
$file->implements(['InterfaceA', 'InterfaceB'])

// Add class implements
$file->add()->implements('InterfaceC')
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/SyntacticSweetener.php'>![Archetype\Endpoints\SyntacticSweetener](https://img.shields.io/badge/-Archetype\Endpoints\SyntacticSweetener-blue)</a>
```php
use LaravelFile as Assistant;

// Make syntax more 'readable' with intermediate words
Assistant::please()->make()->model('App\Car')
    ->and()->add('nickname')->to()->fillable()
    ->also()->add()->a()->trait('HasNickname')
    ->then()->save()
    ->thanks();

// Supported words
$words = [
    'a',
    'also',
    'and',
    'epic',
    'from',
    'have',
    'it',
    'make',
    'please',
    'should',
    'thanks',
    'then',
    'to',
];
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/BelongsTo.php'>![Archetype\Endpoints\Laravel\BelongsTo](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\BelongsTo-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/BelongsToMany.php'>![Archetype\Endpoints\Laravel\BelongsToMany](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\BelongsToMany-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/ModelProperties.php'>![Archetype\Endpoints\Laravel\ModelProperties](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\ModelProperties-blue)</a>
```php
// Property getters (add argument for equivalent setters)
$file->casts();
$file->connection();
$file->dates();
$file->fillable();
$file->guarded();
$file->hidden();
$file->table();
$file->timestamps();
$file->unguarded();
$file->visible();
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/LaravelFileQueryBuilder.php'>![Archetype\Endpoints\Laravel\LaravelFileQueryBuilder](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\LaravelFileQueryBuilder-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/HasMany.php'>![Archetype\Endpoints\Laravel\HasMany](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\HasMany-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/HasOne.php'>![Archetype\Endpoints\Laravel\HasOne](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\HasOne-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/LaravelMaker.php'>![Archetype\Endpoints\Laravel\LaravelMaker](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\LaravelMaker-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/EndpointProvider.php'>![Archetype\Endpoints\EndpointProvider](https://img.shields.io/badge/-Archetype\Endpoints\EndpointProvider-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>
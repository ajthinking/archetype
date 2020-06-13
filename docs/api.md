# File read/write API

[Archetype\Endpoints\PHP\Namespace_](../blob/master/src/Endpoints/PHP/Namespace_.php)
```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
```
<hr>

[Archetype\Endpoints\PHP\MethodNames](../blob/master/src/Endpoints/PHP/MethodNames.php)
```php
// Get class method names
$file->methodNames()
```
<hr>

[Archetype\Endpoints\PHP\TraitUse](../blob/master/src/Endpoints/PHP/TraitUse.php)
```php
// Get class traits
$file->trait()

// Set class traits
INCOMPLETE

// Add class traits
INCOMPLETE
```
<hr>

[Archetype\Endpoints\PHP\Maker](../blob/master/src/Endpoints/PHP/Maker.php)
```php
// UNDOCUMENTED CLASS
```
<hr>

[Archetype\Endpoints\PHP\PHPFileQueryBuilder](../blob/master/src/Endpoints/PHP/PHPFileQueryBuilder.php)
```php
// Get a QueryBuilder instance
PHPFile::query()

// Get all files in root recursively
PHPFile::all()

// Query files in directory
PHPFile::in('app/HTTP')

// Where file->endpoint Equals value
PHPFile::where('className', 'User')

// Where file->endpoints <operator> value
PHPFile::where('implements', 'contains', 'MyInterface')

// Multiple conditions with array
PHPFile::where([['className', 'User'], ['use', 'includes', 'SomeClass']])

// Where callback returns true
PHPFile::where(fn($file) => $file->canUseReflection())

// andWhere is an alias to where
PHPFile::where(...)->andWhere(...)->get()

// Get a collection with results
PHPFile::where(...)->get()

// Get the first match
PHPFile::where(...)->first()
```
<hr>

[Archetype\Endpoints\PHP\ReflectionProxy](../blob/master/src/Endpoints/PHP/ReflectionProxy.php)
```php
// Get ReflectionClass
$file->getReflection()
```
<hr>

[Archetype\Endpoints\PHP\ClassName](../blob/master/src/Endpoints/PHP/ClassName.php)
```php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
```
<hr>

[Archetype\Endpoints\PHP\Method](../blob/master/src/Endpoints/PHP/Method.php)
```php
// UNDOCUMENTED CLASS
```
<hr>

[Archetype\Endpoints\PHP\AstQuery](../blob/master/src/Endpoints/PHP/AstQuery.php)
```php
// Get a AstQueryBuilder instance
$file->astQuery()
```
<hr>

[Archetype\Endpoints\PHP\Property](../blob/master/src/Endpoints/PHP/Property.php)
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

[Archetype\Endpoints\PHP\Extends_](../blob/master/src/Endpoints/PHP/Extends_.php)
```php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
```
<hr>

[Archetype\Endpoints\PHP\Use_](../blob/master/src/Endpoints/PHP/Use_.php)
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

[Archetype\Endpoints\PHP\Implements_](../blob/master/src/Endpoints/PHP/Implements_.php)
```php
// Get class implements
$file->implements()

// Set class implements
$file->implements(['InterfaceA', 'InterfaceB'])

// Add class implements
$file->add()->implements('InterfaceC')
```
<hr>

[Archetype\Endpoints\SyntacticSweetener](../blob/master/src/Endpoints/SyntacticSweetener.php)

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

[Archetype\Endpoints\Laravel\BelongsTo](../blob/master/src/Endpoints/Laravel/BelongsTo.php)
```php
// Add a belongsTo relationship method
$file->belongsTo('Company')
```
<hr>

[Archetype\Endpoints\Laravel\BelongsToMany](../blob/master/src/Endpoints/Laravel/BelongsToMany.php)
```php
// Add a belongsToMany relationship method
$file->belongsToMany('Company')
```
<hr>

[Archetype\Endpoints\Laravel\ModelProperties](../blob/master/src/Endpoints/Laravel/ModelProperties.php)

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

[Archetype\Endpoints\Laravel\LaravelFileQueryBuilder](../blob/master/src/Endpoints/Laravel/LaravelFileQueryBuilder.php)
```php
// Get the User file
LaravelFile::user()

// Query models
LaravelFile::models()

// Query controllers
LaravelFile::controllers()

// Query serviceProviders
LaravelFile::serviceProviders()
```
<hr>

[Archetype\Endpoints\Laravel\HasMany](../blob/master/src/Endpoints/Laravel/HasMany.php)
```php
// Add a hasMany relationship method
$file->hasMany('Company')
```
<hr>

[Archetype\Endpoints\Laravel\HasOne](../blob/master/src/Endpoints/Laravel/HasOne.php)
```php
// Add a hasOne relationship method
$file->hasOne('Company')
```
<hr>

[Archetype\Endpoints\Laravel\LaravelMaker](../blob/master/src/Endpoints/Laravel/LaravelMaker.php)
```php
// UNDOCUMENTED CLASS
```
<hr>

[Archetype\Endpoints\EndpointProvider](../blob/master/src/Endpoints/EndpointProvider.php)
```php
// UNDOCUMENTED CLASS
```
<hr>
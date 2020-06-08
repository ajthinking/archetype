# ```php::archetype```(:fire::fire::fire:);

![tests](https://github.com/ajthinking/archetype/workflows/tests/badge.svg)
![version](https://img.shields.io/packagist/v/ajthinking/archetype?color=blue)
[![Total Downloads](https://poser.pugx.org/ajthinking/archetype/downloads)](https://packagist.org/packages/ajthinking/archetype)
[![License](https://poser.pugx.org/ajthinking/archetype/license)](https://packagist.org/packages/ajthinking/archetype)



Programatically manipulate `PHP` / `Laravel` files on disk with an intuiutive, fluent API. Features include *File-* and *Code/AST* QueryBuilders, an inline PHP Template engine and categorization of read/write operations in `Resource` endpoints.

<img src="https://user-images.githubusercontent.com/3457668/73567244-43055f80-4466-11ea-8103-cc68fba870d7.gif" alt="Intro gif">

## Installation
```
composer require ajthinking/archetype
```

## Usage

### Quick start examples 
```php
use PHPFile;
use LaravelFile;

// find files with the query builder
PHPFile::in('database/migrations')
    ->where('extends', 'Migration')
  	->andWhere('className', 'like', 'Create')
    ->get()
    ->each(function($file) {
        // Do something
        $file->add()->use('Database\CustomMigration')
          ->extends('Database\CustomMigration')
          ->save();
    });

// quickly find the Laravel user file
LaravelFile::user()

// add relationship methods
LaravelFile::user()
    ->hasMany('App\Car')
    ->hasOne('App\Life')
    ->belongsTo(['App\Wife', 'App\Kid'])

// install a package trait
PHPFile::load('app/User.php')
    ->add()->use('Package\Tool')
    ->add()->traitUse('Tool')
    ->save()
    
// debug will write result relative to storage/.debug
LaravelFile::load('app/User.php')
    ->className('MistakeUser')
    ->debug()

// add items to properties
LaravelFile::load('app/User.php')
    ->add()->fillable('message')
    ->add()->casts(['is_admin' => 'boolean'])
    ->add()->hidden('secret')    

// create new files from templates
LaravelFile::make()->model('Beer')
    ->save()

// create new empty file
LaravelFile::make()->file('scripts/custom_script.php')
    ->save()
```

### Available methods

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

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Maker/PHPTemplate.php'>![Archetype\Endpoints\PHP\Maker\PHPTemplate](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Maker\PHPTemplate-blue)</a>
```php
// UNDOCUMENTED CLASS
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
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/LaravelFileQueryBuilder.php'>![Archetype\Endpoints\Laravel\LaravelFileQueryBuilder](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\LaravelFileQueryBuilder-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/LaravelTemplate.php'>![Archetype\Endpoints\Laravel\Maker\LaravelTemplate](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\Maker\LaravelTemplate-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/NamepspacedClass.php'>![Archetype\Endpoints\Laravel\Maker\NamepspacedClass](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\Maker\NamepspacedClass-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Model.php'>![Archetype\Endpoints\Laravel\Maker\Model](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\Maker\Model-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Unimplemented.php'>![Archetype\Endpoints\Laravel\Maker\Unimplemented](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\Maker\Unimplemented-blue)</a>
```php
// UNDOCUMENTED CLASS
```
<hr>

<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/Laravel/Maker/Command.php'>![Archetype\Endpoints\Laravel\Maker\Command](https://img.shields.io/badge/-Archetype\Endpoints\Laravel\Maker\Command-blue)</a>
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

### Querying the Abstract Syntax Tree (under review)
As seen in the previous examples we can query and manipulate nodes with simple or primitive values, such as *strings* and *arrays*. However, if we want to perform custom or more in dept queries we must use the `ASTQueryBuilder`.

Example: how can we fetch explicit column names in a migration file?

```php
LaravelFile::load('database/migrations/2014_10_12_000000_create_users_table.php')
    ->astQuery() // get a ASTQueryBuilder

    ->method()
        ->named('up')
    ->staticCall()
        ->where('class', 'Schema')
        ->named('create')
    ->args
    ->closure()
    ->stmts
    ->methodCall()
        ->where('var->name', 'table')
    ->args
	->value
	->value
	->get(); // exit ASTQueryBuilder, get a Collection        
```

The ASTQueryBuilder examines all possible paths and automatically terminates those that cant complete the query:

<img src="https://user-images.githubusercontent.com/3457668/83963046-25785480-a8a3-11ea-9224-b04fa8cebb81.png" width="600px">

* Three kinds of methods are provided (hinted with indentation in the code example)
    * Traversing (`methods`,`staticCalls`,`firstArg` ...)
    * Filtering (`named`, `whereClass` ...)
    * Resolving (`getValue`)
* The ASTQueryBuilder relies entirely on [nikic/php-parser](https://github.com/nikic/php-parser). To understand this syntax better tinker with `dd($file->ast()`. 

### Template engine (under review)
Let's make a snippet for a method we want to insert. Start by creating a file `storage/archetype/snippets/my-stuff.php` like shown below. In the file, we put our template code including any encapsuling constructs (in our case we will have to put a class since methods only exists inside classes). Name anything you want to be configurable with a handle for instance `'___TARGET_CLASS___'`. Even your snippet name itself may be a handle as long as it is unique.

```php
<?php

/**
 * Optionally use FAKE names to silence IDE warnings
 */
use Archetype\Support\FakeName; 
use Archetype\Support\FakeName as ANY;
use Archetype\Support\FakeName as ___TARGET_CLASS___;

/**
 * This is just a placeholder class where we can add our snippets
 */
class _ extends FakeName
{
    /**
    * ___DOC_BLOCK___
    */
    public function mySpecialMethod($arg)
    {
        $want = abs($arg);
        return $this->doSomethingWith(___TARGET_CLASS___::class, 'my template')
            ->use(ANY::thing(new static('you' . $want)));
    }    
}
```

Your snippet is then instantly available anywhere in your code:
```php
use Archetype\Support\Snippet;

// Get the snippet
Snippet::mySpecialMethod()

// Pass an array of replacement pairs to replace any handles:
Snippet::mySpecialMethod([
    '___DOC_BLOCK___' => 'Inserted with archetype :)',
    '___TARGET_CLASS___' => 'App\Rocket'
]);

// Integrated example
PHPFile::load('app/User.php')
    ->addMethod(
        Snippet::mySpecialMethod([
            // replacement pairs ...
        ])
    )->save();
````

> :information_source: The `Snippet` class currently only supports templates on *class methods*.

### Errors
If a file can't be parsed, a `FileParseError` will be thrown. This can happen if you try to explicitly load the file *but also* when performing queries matching problematic files.

To see *all* offending files run `php artisan file:errors`. To ignore files with problems, put them in `config/archetype.php` -> `ignored_paths`.

### Limitations / Missing features
In general this package assumes code to be parsed follows guidellines and conventions from [PSR](https://www.php-fig.org/psr/) and [Laravel](https://laravel.com/docs). Some examples are listed below.

* Can't use group use syntax (`use Something\{X, Y};`)

* Assumes one class per file

* Assumes no multiple/grouped property declarations (`protected $a, $b = 1;`)

## Configuration
    php artisan vendor:publish --provider="Archetype\ServiceProvider"

## Contributing

### Development installation
The test suite requires that you are inside laravel application
```bash
laravel new host
cd host
git clone git@github.com:ajthinking/archetype.git packages/ajthinking/archetype
```
Add this to the host projects `composer.json`
```json
    "repositories": [
        {
            "type": "path",
            "url": "/PATH/TO/PROJECTS/host/packages/ajthinking/archetype"
        }
    ],
```
Then,
```bash
composer require ajthinking/archetype @dev
```
Finally in host root run
```bash
vendor/phpunit/phpunit/phpunit packages/ajthinking/archetype/tests
```

### Roadmap
PRs and issues are welcome. Have a look at the [Trello board](https://trello.com/b/1M2VRnoQ/archetype) for planned features.

<a href="https://trello.com/b/1M2VRnoQ/archetype">
    <img src="https://user-images.githubusercontent.com/3457668/83963060-332dda00-a8a3-11ea-844d-5d4d4d6987ff.png" width="600px">
</a>

## License
MIT


## Acknowledgements
* Built with [nikic/php-parser](https://github.com/nikic/php-parser)
* PSR Printing fixes borrowed from [tcopestake/PHP-Parser-PSR-2-pretty-printer](https://github.com/tcopestake/PHP-Parser-PSR-2-pretty-printer)


## Like this package?
<a href="https://github.com/ajthinking/archetype/stargazers" >Star it :star: </a>

[Say hi: @ajthinking :gem:](https://twitter.com/ajthinking)

[Github Sponsors :octocat::heart:](https://github.com/sponsors/ajthinking)

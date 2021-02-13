# ```php::archetype```(:fire::fire::fire:);

### Enabling Rapid-Application-Development-tools, PR-bots, code analyzers and other things

![tests](https://github.com/ajthinking/archetype/workflows/tests/badge.svg)
![version](https://img.shields.io/packagist/v/ajthinking/archetype?color=blue)

* Programatically modify `PHPFile`s and `LaravelFile`s  with an intuiutive top level read/write API
* Read/write on classes, framework- and language constructs using `FileQueryBuilders` and `AbstractSyntaxTreeQueryBuilders`
* Extract Application entity schemas
* Add `Snippet`s with an inline PHP Template engine


<!--<img src="https://user-images.githubusercontent.com/3457668/73567244-43055f80-4466-11ea-8103-cc68fba870d7.gif" alt="Intro gif">-->

## Table of Content
  * [Installation](#installation)
  * [Usage](#usage)
    + [PHPFile read/write API](#-phpfile--read-write-api)
    + [LaravelFile read/write API](#-laravelfile--read-write-api)
    + [File QueryBuilder](#file-querybuilder)
    + [Abstract Syntax Tree QueryBuilder](#abstract-syntax-tree-querybuilder)
    + [Laravel schema](#laravel-schema)
    + [Template engine](#template-engine)
    + [Errors](#errors---)
    + [Limitations / Missing features](#limitations---missing-features)
  * [Configuration](#configuration)
  * [Contributing](#contributing)
    + [Development installation](#development-installation)
    + [Roadmap](#roadmap)
  * [License](#license)
  * [Acknowledgements](#acknowledgements)
  * [Like this package?](#like-this-package-)

## Installation
```
composer require ajthinking/archetype
```
> Requires UNIX filesystem, PHP >= 7 and Laravel >= 7

## Usage

### `PHPFile` read/write API

```php
use PHPFile;

// Create new files
PHPFile::make()->class('acme/Product.php')
    ->use('Shippable')
    ->add()->trait('Acme\Traits\Shippable')
    ->public()->property('stock', -1)
    ->save();
```

```php
// Modify existing files  
PHPFile::load('app/Models/User.php')
    ->className('NewClassName')
    ->save();
```

### `LaravelFile` read/write API

```php
use LaravelFile; // extends PHPFile

// Expanding on our User model
LaravelFile::user()
    ->add()->use(['App\Traits\Dumpable', 'App\Contracts\PlayerInterface'])
    ->add()->implements('PlayerInterface')
    ->add()->trait('Dumpable')
    ->table('gdpr_users')
    ->add()->fillable('nickname')
    ->remove()->hidden()
    ->empty()->casts()
    ->hasMany('App\Game')
    ->belongsTo('App\Guild')
    ->save();
```

Result:

<img src="https://user-images.githubusercontent.com/3457668/84030881-1376de80-a995-11ea-9ab0-431eaf9401a7.png" width=600>

> [Review full API documentation here](https://github.com/ajthinking/archetype/blob/master/docs/api.md) :point_left:

### File QueryBuilder
Filter and retrieve a set of files to interact with. 

```php
// find files with the query builder
PHPFile::in('database/migrations')
    ->where('extends', 'Migration')
    ->andWhere('className', 'like', 'Create')
    ->get() // returns Collection of PHPFiles

// Quickly find the Laravel User file
$file = LaravelFile::user();

// Quickly find Laravel specific files
LaravelFile::models()->get();
LaravelFile::controllers()->get();
LaravelFile::serviceProviders()->get();
// ...
```

> [See a few more QueryBuilder examples in the tests](https://github.com/ajthinking/archetype/blob/master/tests/Unit/Endpoints/PHP/PHPFileQueryBuilderTest.php) :point_left:

### Abstract Syntax Tree QueryBuilder
As seen in the previous examples we can query and manipulate nodes with simple or primitive values, such as *strings* and *arrays*. However, if we want to perform custom or more in dept queries we must use the `ASTQueryBuilder`.

Example: how can we fetch explicit column names in a migration file?

```php
LaravelFile::load('database/migrations/2014_10_12_000000_create_users_table.php')
    ->astQuery() // get a ASTQueryBuilder

    ->method()
        ->where('name->name', 'up')
    ->staticCall()
        ->where('class', 'Schema')
        ->where('name->name', 'create')
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

The ASTQueryBuilder relies entirely on [nikic/php-parser](https://github.com/nikic/php-parser). Available query methods mirror the `PhpParser` types and properties. To understand this syntax better you may want to tinker with `dd($file->ast())` while building your queries. Basic conventions are listed below. 

* Traverse into *nodes* by using methods (`method()`,`staticCall()` ...)
* Traverse into *node properties* by accessing properties (`args`,`stmts` ...)    
* Filter results with `where(...)`
* Resolving matching paths with `get()`

> `ASTQueryBuilder` also supports *removing*, *replacing* and *injecting* nodes :wrench:

```php
// Replace a node property
$file->astQuery()
    ->class()
    ->name
    ->replaceProperty('name', $newClassName)
    ->commit() // updates the file's AST
    ->end() // exit query
    ->save() 
```

> [More ASTQueryBuilder examples here](https://github.com/ajthinking/archetype/blob/master/docs/src/Support/AST/ASTQueryBuilder.md) :point_left: 

### Laravel schema 
Use the `LaravelSchema` class to get an app schema.

```php
use Archetype\Schema\LaravelSchema;

LaravelSchema::get();
```

```json
{
    "entities": [
        {
            "model": "App\\User",
            "table": "users",
            "columns": {
                "id": {
                    "name": "id",
                    "type": {},
                    "default": null,
                    "notnull": true,
                    "length": null,
                    "precision": 10,
                    "scale": 0,
                    "fixed": false,
                    "unsigned": false,
                    "autoincrement": true,
                    "columnDefinition": null,
                    "comment": null
                },
                "name": {
                    "name": "name",
                    "type": {},
                    "default": null,
                    "notnull": true,
                    "length": null,
                    "precision": 10,
                    "scale": 0,
                    "fixed": false,
                    "unsigned": false,
                    "autoincrement": false,
                    "columnDefinition": null,
                    "comment": null,
                    "collation": "BINARY"
                }
            }
        }
    ],
    "strategy_used": "Archetype\\Schema\\Strategies\\FromDatabase",
    "log": []
}
```

> Schema feature is under construction ⚠

### Template engine
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
PHPFile::load('app/Models/User.php')
    ->addMethod(
        Snippet::mySpecialMethod([
            // replacement pairs ...
        ])
    )->save();
````

> :information_source: The `Snippet` class currently only supports templates on *class methods*.

### A note on Facades
You may use either of the following
```php
// Using class
(new \Archetype\PHPFile)->load('...');

// Using facade
PHPFile::load('...');

// Using facade explicitly
use Archetype\Facades\PHPFile;
PHPFile::load('...'); // Using facade explicitly
```

### Errors 😵
If a file can't be parsed, a `FileParseError` will be thrown. This can happen if you try to explicitly load the file *but also* when performing queries matching problematic files.

To see *all* offending files run `php artisan archetype:errors`. To ignore files with problems, put them in `config/archetype.php` -> `ignored_paths`.

### Limitations / Missing features
In general this package assumes code to be parsed follows guidellines and conventions from [PSR](https://www.php-fig.org/psr/) and [Laravel](https://laravel.com/docs). Some examples are listed below.

* Requires UNIX based file system - no windows support <img src="https://img.shields.io/badge/help wanted-blue">

* Can't use group use syntax (`use Something\{X, Y};`)

* Assumes one class per file

* Assumes no multiple/grouped property declarations (`protected $a, $b = 1;`)

## Configuration
    php artisan vendor:publish --provider="Archetype\ServiceProvider"

## Contributing

### Development installation
```
git clone git@github.com:ajthinking/archetype.git
cd archetype
composer install
./vendor/bin/phpunit tests
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
* Schema extractor based on [mpociot/laravel-test-factory-helper](https://github.com/mpociot/laravel-test-factory-helper)


## Like this package?
<a href="https://github.com/ajthinking/archetype/stargazers" >Star it :star: </a>

[Say hi: @ajthinking :gem:](https://twitter.com/ajthinking)

[Github Sponsors :octocat::heart:](https://github.com/sponsors/ajthinking)

# ```php::archetype```(:fire::fire::fire:);

![tests](https://github.com/ajthinking/archetype/workflows/tests/badge.svg)
![version](https://img.shields.io/packagist/v/ajthinking/archetype?color=blue)
[![Total Downloads](https://poser.pugx.org/ajthinking/archetype/downloads)](https://packagist.org/packages/ajthinking/archetype)
[![License](https://poser.pugx.org/ajthinking/archetype/license)](https://packagist.org/packages/ajthinking/archetype)

* Programatically modify `PHPFile`s and `LaravelFile`s  with an intuiutive read/write API
* Dive *into* files and framework/language constructs using `FileQueryBuilder`s and an `AbstractSyntaxTreeQueryBuilder`
* Inline PHP Template engine

<img src="https://user-images.githubusercontent.com/3457668/73567244-43055f80-4466-11ea-8103-cc68fba870d7.gif" alt="Intro gif">

## Table of Content
  * [Installation](#installation)
  * [Usage](#usage)
    + [File read/write API](#file-read/write-api)
    + [File QueryBuilder](#file-querybuilder)
    + [Abstract Syntax Tree QueryBuilder](#abstract-syntax-tree-querybuilder)
    + [Template engine](#template-engine)
    + [Finding errors](#errors)
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

## Usage

### File read/write API

```php
use PHPFile;

// Create file  
PHPFile::make()->class('acme/Product.php')
    ->use('Shippable')
    ->add()->trait('Shippable')
    ->public()->property('stock', -1)
    ->save();
```

```php
// Modify existing files  
PHPFile::load('app/User.php')
    ->className('NewClassName')
    ->save();
```

```php
// LaravelFile extends PHPFile
use LaravelFile;

LaravelFile::user()
    ->add()->use(['App\Traits\Dumpable', 'App\Contracts\PlayerInterface'])
    ->add()->trait('Dumpable')
    ->table('gdpr_users')
    ->add()->fillable('nickname')
    ->remove()->hidden()
    ->empty()->casts()
    ->hasMany('App\Game')
    ->belongsTo('App\Guild')
    ->save();
```

Running the `LaravelFile` script above will save the following to disk:

<img src="https://user-images.githubusercontent.com/3457668/84030881-1376de80-a995-11ea-9ab0-431eaf9401a7.png" width=600>

```php
// Each setter method also act as getter when argument is omitted
echo LaravelFile::load('app/User.php')->fillable();

// ['name', 'email', 'password']
```

> [Review full API documentation here](https://github.com/ajthinking/archetype/blob/master/docs/api.md) :point_left:

### File QueryBuilder
Filter and retrieve a set of files to interact with. 

```php
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

// Quickly find the Laravel User file
$file = LaravelFile::user();

// Quickly find Laravel specific files
LaravelFile::models()->get();
LaravelFile::controllers()->get();
LaravelFile::serviceProviders()->get();
// ...
```

> [Review full QueryBuilder Documentation here](https://github.com/ajthinking/archetype/blob/master/docs/querybuilder.md) :point_left:

### Abstract Syntax Tree QueryBuilder
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

To see *all* offending files run `php artisan archetype:errors`. To ignore files with problems, put them in `config/archetype.php` -> `ignored_paths`.

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

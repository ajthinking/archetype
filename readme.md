![image](https://user-images.githubusercontent.com/3457668/148050728-f80fb02c-e24e-4957-b960-8e52796fbf23.png)

### Enabling Rapid-Application-Development-tools, PR-bots, code analyzers and other things

![tests](https://github.com/ajthinking/archetype/workflows/tests/badge.svg)
![version](https://img.shields.io/packagist/v/ajthinking/archetype?color=blue)
[![Total Downloads](https://img.shields.io/packagist/dt/ajthinking/archetype.svg)](https://packagist.org/packages/ajthinking/archetype)

* Programatically modify php files with an intuitive top level read/write API
* Read/write on classes, framework- and language constructs using `FileQueryBuilders` and `AbstractSyntaxTreeQueryBuilders`

## Getting started
```bash
composer require ajthinking/archetype
```
> 

That's it! Check out introduction of concepts below or review the [API examples](docs.md)
 
## `PHPFile` read/write API

```php
use Archetype\Facades\PHPFile;

// Create new files
PHPFile::make()->class(\Acme\Product::class)
    ->use('Shippable')
    ->public()->property('stock', -1)
    ->save();
```

```php
// Modify existing files  
PHPFile::load(\App\Models\User::class)
    ->className('NewClassName')
    ->save();
```

## `LaravelFile` read/write API

```php example
use Archetype\Facades\LaravelFile; // extends PHPFile

// Expanding on our User model
LaravelFile::user()
    ->add()->use(['App\Traits\Dumpable', 'App\Contracts\PlayerInterface'])
    ->add()->implements('PlayerInterface')
    ->table('gdpr_users')
    ->add()->fillable('nickname')
    ->remove()->hidden()
    ->empty()->casts()
    ->hasMany('App\Game')
    ->belongsTo('App\Guild')
    ->save()
    ->render();
```

<details><summary>Show output</summary>

```php
<?php

namespace App\Models;

use App\Contracts\PlayerInterface;
use App\Traits\Dumpable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements PlayerInterface
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'gdpr_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];
    
    /**
     * Get the associated Guild
     */
    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }
    
    /**
     * Get the associated Games
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}

```

</details>

## File QueryBuilders
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

## Abstract Syntax Tree QueryBuilder

As seen in the previous examples we can query and manipulate nodes with simple or primitive values, such as *strings* and *arrays*. However, if we want to perform custom or more in dept queries we must use the `ASTQueryBuilder`.

Example: how can we fetch explicit column names in a migration file?

```php
LaravelFile::load('database/migrations/2014_10_12_000000_create_users_table.php')
    ->astQuery() // get a ASTQueryBuilder
    ->classMethod()
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
    ->get();
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

## Errors 😵
If a file can't be parsed, a `FileParseError` will be thrown. This can happen if you try to explicitly load a broken file *but also* when performing queries matching one or more problematic files.

To see *all* offending files run `php artisan archetype:errors`. To ignore files with problems, put them in `config/archetype.php` -> `ignored_paths`.

## Configuration
```bash
php artisan vendor:publish --provider="Archetype\ServiceProvider"
```

## Requirmenst
* UNIX filesystem
* PHP >= 7.4
* Laravel >= 7

## Contributing
PRs and issues are welcome :pray: Feel free to take a stab at an [incomplete test](https://github.com/ajthinking/archetype/search?q=%24this-%3EmarkTestIncomplete).
### Development installation
```
git clone git@github.com:ajthinking/archetype.git
cd archetype
composer install
./vendor/bin/pest
```


## License
MIT


## Acknowledgements
* Built with [nikic/php-parser](https://github.com/nikic/php-parser)
* PSR Printing fixes borrowed from [tcopestake/PHP-Parser-PSR-2-pretty-printer](https://github.com/tcopestake/PHP-Parser-PSR-2-pretty-printer)


## Like this package?
<a href="https://github.com/ajthinking/archetype/stargazers" >Star it :star: </a>

[Say hi: @ajthinking :gem:](https://twitter.com/ajthinking)

[Github Sponsors :octocat::heart:](https://github.com/sponsors/ajthinking)

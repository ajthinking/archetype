![image](https://user-images.githubusercontent.com/3457668/148050728-f80fb02c-e24e-4957-b960-8e52796fbf23.png)

### Enabling Rapid-Application-Development-tools, PR-bots, code analyzers and other things

[![tests](https://github.com/ajthinking/archetype/actions/workflows/tests.yml/badge.svg)](https://github.com/ajthinking/archetype/actions/workflows/tests.yml)
![version](https://img.shields.io/packagist/v/ajthinking/archetype?color=blue)
[![Total Downloads](https://img.shields.io/packagist/dt/ajthinking/archetype.svg)](https://packagist.org/packages/ajthinking/archetype)

* Programatically modify php files with an intuitive top level read/write API
* Read/write on classes, framework- and language constructs using `FileQueryBuilders` and `AbstractSyntaxTreeQueryBuilders`
* Do the same from a terminal — or from an AI agent — with the [`archetype` command line](#command-line)

## Getting started
```bash
composer require ajthinking/archetype
```
> 

That's it! Check out introduction of concepts below or review the [API examples](docs.md)

## Supported versions

| Archetype | PHP     | Laravel  | php-parser |
| --------- | ------- | -------- | ---------- |
| 2.x       | 8.1–8.4 | 10, 11, 12, 13 | ^5.0 |
| 1.x       | 7.4–8.1 | 6–9      | ^4.11      |

> **On 1.x?** Upgrade to 2.x. Version 1 pins `nikic/php-parser` to `^4.11`, which
> cannot be installed alongside anything that needs php-parser 5 — Pest 3 and
> newer included. If Composer refuses to resolve `ajthinking/archetype`, that is
> why. The 1.x → 2.x upgrade requires no changes to your own code.
 
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

## Command line

Everything above is also a command. Each operation is an Artisan command under
`archetype:`, and the `archetype` binary is a shorthand that finds your
application and forwards to it:

```bash
./vendor/bin/archetype inspect app/Models/User.php
# is the same as
php artisan archetype:inspect app/Models/User.php
```

Run `archetype` with no arguments for the full list. There are 26 operations;
these are the shape of them:

```bash
# read
archetype inspect app/Models/User.php               # structure, without method bodies
archetype inspect app/Models/User.php props methods # only the parts you want
archetype show app/Http/Requests/StoreTask.php rules
archetype find app --type=models --uses-trait=SoftDeletes

# write
archetype add-to-property app/Models/User.php fillable nickname
archetype set-casts app/Models/User.php archived_at=datetime status=Status::class
archetype add-relation app/Models/Project.php belongsToMany Label --table=label_project --with-timestamps
archetype set-array-key app/Http/Requests/StoreTask.php rules due_at 'nullable|date'
archetype add-case app/Enums/Status.php OnHold on_hold
archetype add-method app/Models/User.php --code='public function scopeActive($q) { return $q->where("active", true); }'
```

The full reference is in [docs.md](docs.md#command-line-reference).

### What a target is

Every operation takes one target, which is a path, a class name, or a directory:

```bash
archetype add-trait app/Models/User.php Auditable   # one file
archetype add-trait 'App\Models\User' Auditable     # the same file
archetype add-trait app/Models Auditable            # every class under app/Models
```

A directory target can be narrowed with `--extends`, `--implements`,
`--uses-trait` and `--matching`.

### What a mutation answers with

```bash
$ archetype add-to-property app/Models/User.php fillable nickname
OK app/Models/User.php $fillable +1
@@ 24 @@
+         'nickname',
      ];
```

Three rules hold for every operation that writes:

* it re-renders the file and compares, so a change that matched nothing is an
  error and exits non-zero — never a success that wrote nothing;
* it answers with a diff, so you do not have to read the file back to see what
  happened;
* a change already applied is `SKIP`, not `OK` and not an error, so operations
  are safe to repeat.

`--dry-run` shows the same diff without writing. `--json` gives every operation a
machine-readable answer instead.

### Several changes in one call

```bash
archetype apply <<'EOF'
add-to-property app/Models/Project.php fillable budget_cents
set-casts app/Models/Project.php budget_cents=integer
add-relation app/Models/Project.php hasMany Task
add-implements app/Models/Project.php 'App\Contracts\Auditable'
EOF
```

## Errors 😵
If a file can't be parsed, a `FileParseError` will be thrown. This can happen if you try to explicitly load a broken file *but also* when performing queries matching one or more problematic files.

To see *all* offending files run `php artisan archetype:errors`. To ignore files with problems, put them in `config/archetype.php` -> `ignored_paths`.

## Configuration
```bash
php artisan vendor:publish --provider="Archetype\ServiceProvider"
```

## Requirements
* UNIX filesystem
* PHP >= 8.1
* Laravel >= 10

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

# ```PHPFile::manipulator```(:fire::fire::fire:);

[![Latest Stable Version](https://poser.pugx.org/ajthinking/php-file-manipulator/v/stable)](https://packagist.org/packages/ajthinking/php-file-manipulator)
[![License](https://poser.pugx.org/ajthinking/php-file-manipulator/license)](https://packagist.org/packages/ajthinking/php-file-manipulator)


Programatically manipulate `PHP` / `Laravel` files on disk with an intuiutive, fluent API. Features include a file QueryBuilder, Template engine and categorization of read/write operations into `Resource` endpoints.

## Installation
```
composer require ajthinking/php-file-manipulator
```

## Quick start examples 
```php
use PHPFile;
use LaravelFile;

// find files with the query builder
PHPFile::in('database/migrations')
    ->where('classExtends', 'Migration')
    ->get()
    ->each(function($file) {
        // Do something
        $file->classExtends('Database\CustomMigration')->save()
    });

// add relationship methods
LaravelFile::load('app/User.php')
    ->addHasMany('App\Car')
    ->addHasOne('App\Life')
    ->addBelongsTo('App\Wife')
    ->save()

// list class methods
PHPFile::load('app/User.php')
    ->classMethods()

// move User.php to a Models directory
PHPFile::load('app/User.php')
    ->namespace('App\Models')
    ->move('app/Models/User.php')

// install a package trait
PHPFile::load('app/User.php')
    ->addUseStatements('Package\Tool')
    ->addTraitUseStatement('Tool')
    ->save()

// add a route
LaravelFile::load('routes/web.php')
    ->addRoute('dummy', 'Controller@method')
    ->save()
    
// preview will write result relative to storage/.preview
LaravelFile::load('app/User.php')
    ->setClassName('Mistake')
    ->preview()

// add items to protected properties
LaravelFile::load('app/User.php')
    ->addFillable('message')
    ->addCasts(['is_admin' => 'boolean'])
    ->addHidden('secret')    

// create new files from templates
LaravelFile::model('Beer')
    ->save()
LaravelFile::controller('BeerController')
    ->save()

// many in one go
LaravelFile::create('Beer', ['model', 'controller', 'migration'])

```

### Example Artisan command
A command ```php artisan file:demo``` is supplied to showcase some practical use cases. Check out the source [here](src/Commands/DemoCommand.php).

<img src="docs/DemoCommand.png" width="600px">

### Build your own templates
Let's make a snippet for a method we want to insert. Start by creating a file `storage/php-file-manipulator/snippets/my-stuff.php` like shown below. In the file, we put our template code including any encapsuling constructs (in our case we will have to put a class since methods only exists inside classes). Name anything you want to be configurable with a handle for instance `'___TARGET_CLASS___'`. Even your snippet name itself may be a handle as long as it is unique.

```php
<?php

/**
 * Optionally use FAKE names to silence IDE warnings
 */
use Ajthinking\PHPFileManipulator\Support\FakeName; 
use Ajthinking\PHPFileManipulator\Support\FakeName as ANY;
use Ajthinking\PHPFileManipulator\Support\FakeName as ___TARGET_CLASS___;

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
use Ajthinking\PHPFileManipulator\Support\Snippet;

// Get the snippet
Snippet::mySpecialMethod()

// Pass an array of replacement pairs to replace any handles:
Snippet::mySpecialMethod([
    '___DOC_BLOCK___' => 'Inserted with php-file-manipulator :)',
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

The `Snippet` class currently only supports templates on *class methods*.

## Gotchas
> :warning: Currently when reading, the package will not traverse into includes, traits or parent classes. It is up to you ta handle that.

> :warning: This package assumes code follows guidellines and conventions from [PSR](https://www.php-fig.org/psr/) and [Laravel](https://laravel.com/docs). Examples: use no more than one class and namespace per file, refrain from multiple property declarations in same line, avoid group use statements etc.


## Contributing
PRs and issues are welcome. Have a look at the [Trello board](https://trello.com/b/1M2VRnoQ/php-file-manipulator) for planned features.

<a href="https://trello.com/b/1M2VRnoQ/php-file-manipulator">
    <img src="docs/trello.png" width="600px">
</a>

### Running tests
The test suite currently requires that you have the package installed in a laravel project:
> packages/Ajthinking/PHPFileManipulator

Then, from the host project root run
```bash
vendor/phpunit/phpunit/phpunit packages/Ajthinking/PHPFileManipulator/tests
```

## License
MIT

## Acknowledgements
* Built with [nikic/php-parser](https://github.com/nikic/php-parser)
* PSR Printing fixes borrowed from [tcopestake/PHP-Parser-PSR-2-pretty-printer](https://github.com/tcopestake/PHP-Parser-PSR-2-pretty-printer)

## Like this package?

<a href="https://www.https://github.com/sponsors/ajthinking" >GitHub Sponsors :heart: :octocat: </a>

<a href="https://www.patreon.com/ajthinking" >Patreon :rocket: </a>

[Follow @ajthinking :gem:](https://twitter.com/ajthinking)

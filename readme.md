# ```PHPFile::manipulator```(:fire::fire::fire:);
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

### Build your own compilable templates
Go to `snippets.php` and add your templates:

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
    public function ___METHOD_NAME___($arg)
    {
        $want = abs($arg);
        return $this->doSomethingWith(___TARGET_CLASS___::class, 'my template')
            ->use(ANY::thing(new static('you' . $want)));
    }
}
```

Your snippet is instantly available elsewhere:
```php
PHPFile::load('app/User.php')
    ->addSnippet('myMethod');
```

## Notes
> :warning: Currently when reading, the package will not traverse into includes, traits or parent classes. It is up to you ta handle that.

> :warning: This package assumes code follows guidellines and conventions from [PSR](https://www.php-fig.org/psr/) and [Laravel](https://laravel.com/docs). Examples: use no more than one class and namespace per file, refrain from multiple property declarations in same line, avoid group use statements etc.


## Contributing
PRs and issues are welcome. Ask me for invitation to [Trello](https://trello.com/b/1M2VRnoQ/php-file-manipulator)  

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

<a href="https://www.patreon.com/ajthinking" >Patreon :rocket: </a>

<a href="https://www.https://github.com/sponsors/ajthinking" >GitHub Sponsors :heart: :octocat: </a>

[Follow @ajthinking :gem:](https://twitter.com/ajthinking)

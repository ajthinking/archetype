# ```PHPFile::manipulator```(:fire::fire::fire:);
Programatically manipulate `PHP` / `Laravel` files on disk with an intuiutive, fluent API. Features include a file QueryBuilder, Template engine and categorization of read/write operations into `Resource` endpoints.

## Installation
```
composer require ajthinking/php-file-manipulator
```

## Quick start examples

### 
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

### Experimental feature: inline method builder
```php

// Go to snippets.php

// if needed set up fake names
use PHPFile\FakeName as User;
use PHPFile\FakeName as Car;

// name your snippet
$snippet = PHPFile::snippet('myMethod',
    // put snippet code
    function($any, $nbr, $of, Car $params) {
        Car::wow()->also(User $user)
            ->many()->write_inline('CODE');
        return $this::static('anything') ? 13 : 37;
    }
);

// Your snippet is instantly available elsewhere
PHPFile::load('app/User.php')
    ->addSnippet('myMethod');



```

## Example Artisan command
This package provides a demo command to showcase possible use cases:

 ```bash 
 php artisan file:demo
 ```

<img src="docs/DemoCommand.png" width="600px">



## Running tests
```bash
# the test suite requires that you have the package installed in a laravel project
vendor/phpunit/phpunit/phpunit packages/Ajthinking/PHPFileManipulator/tests
```

## License
MIT

## Contributing
PRs and issues are welcome. 

## TODO


| task | status |
|------|--------|
| Make the test work without being inside a host application| - |
| How handle base_path() when not in a Laravel app? | - |
| Create a dedicated Storage disk (storage/php-file-manipulator/preview etc) ??? | - |
| It should be able to add use statements with aliases | - |
| It should be capable of reading/writing `GroupUse`, example:  `use Package\{Alfa, Beta};` | - |
| Simplify adding multiline docblocks on methods | - |
| Group related resources (PHP/Laravel Reources in separate folders) | - |
| Can it resolve resources from parent classes and traits??? | - |
| Add the missing relationships: https://laravel.com/docs/6.x/eloquent-relationships#introduction | - |
| Make a minimal querybuilder | - |

## API

| Resource | GET | SET | ADD | REMOVE |
|------|--------|--------|--------|--------|
| Namespace | yes | yes | | |
| Uses | yes | yes | | |
| ClassName | ```$file->className()``` | ```$file->className('newName)``` | | |
| ClassExtends | yes | yes | yes | yes |
| ClassImplements | yes | yes | yes | yes |
| HasManyMethods |  |  | ```$file->addHasManyMethods(['App\Car'])``` |  |

## Acknowledgements
* Built with [nikic/php-parser](https://github.com/nikic/php-parser)
* PSR Printing fixes borrowed from [tcopestake/PHP-Parser-PSR-2-pretty-printer](https://github.com/tcopestake/PHP-Parser-PSR-2-pretty-printer)

## Stay tuned!
Follow me on twitter: [@ajthinking](https://twitter.com/ajthinking)

<a href="https://www.patreon.com/ajthinking" >Help me continue this work | Patreon</a>

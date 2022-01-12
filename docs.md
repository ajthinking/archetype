## API examples

### Make an empty file
```php
PHPFile::make()->file('dummy.php')
```

### Make a class
```php
PHPFile::make()->class(\App\Models\Car::class)
```

### Load an existing file
```php
PHPFile::load('app/Models/User.php')
```

### Load an existing class by name
```php
PHPFile::load(\App\Models\User::class)
```

### Make a file from a string
```php
PHPFile::fromString('<?php $hey = 1337;')
```

### Make a file from a pseudo php string
```php
PHPFile::addMissingTags()->fromString('$hey = 1337')
```

### Render file
```php
$file->render()
```

### Save file
Saves a file to disk
```php
$file->save()
```

### Save file to a new location
```php
$file->save('app/helpers.php')
```

### Get class name
```php
$file->className();
```

### Change class name
```php
$file->className('NewName')
```

### Get a class constant
```php
$file->classConstant('HOME')
```

### Set a class constant
```php
$file->classConstant('HOME', '/new/home')
```

### Remove a class constant
```php
$file->remove()->classConstant('HOME')
```

### Get class extends
```php
$file->extends();
```

### Set class extends
```php
$file->extends(SomeBaseClass::class)
```

### Get class implements
```php
$file->implements();
```

### Set class implements
```php
$file->implements([SomeInterface::class])
```

### Get class method names
```php
$file->methodNames()
```

### Get namespace
```php
$file->namespace();
```

### Set class namespace
```php
$file->namespace('New\Namespace')
```

### Get a property
```php
$file->property('table');
```

### Set a property
```php
$file->property('table', 'new_table');
```

### Set property visibility
```php
$file->private()->property('table', 'secret');
```

### Remove property
```php
$file->remove()->property('table');
```

### Clear a property
```php
$file->clear()->property('fillable');
```

### Empty property
```php
$file->empty()->property('fillable');
```

### Add item to array property
```php
$file->add('column')->to()->property('fillable');
```

### Get use statements
```php
$file->use()
```

### Set use statements
```php
$file->use([
	Class1::class,
	Class2::class,
])
```

### Add use statements
```php
$file->add()->use([
	Extra1::class,
	Extra2::class,
])
```
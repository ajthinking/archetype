<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/PHP/Property.php'>![Property](https://img.shields.io/badge/-Archetype\Endpoints\PHP\Property-blue)
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
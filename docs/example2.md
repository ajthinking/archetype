```php
// Get file namespace
$file->namespace()

// Set file namespace
$file->namespace('App\Models')

// Remove file namespace
$file->remove()->namespace()
``````php
// Get class method names
$file->methodNames()
``````php
// Get class traits
$file->trait()

// Set class traits
INCOMPLETE

// Add class traits
INCOMPLETE
``````php



``````php















``````php
// Get ReflectionClass
$file->getReflection()
``````php
// Get file class name
$file->className()

// Set file class name
$file->className('MyClass')
``````php

``````php
// Get a AstQueryBuilder instance
$file->astQuery()
``````php
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
``````php
// Get class extends
$file->extends()

// Set class extends
$file->extends('App\BaseProduct')
``````php





``````php
// Get file uses
$file->use()

// Set file uses
$file->use(['ClassA', 'classB'])

// Use with alias
$file->use('ClassA as Ajthinking')

// Add file uses
$file->add()->use('AdditionalClass')
``````php
// Get class implements
$file->implements()

// Set class implements
$file->implements(['InterfaceA', 'InterfaceB'])

// Add class implements
$file->add()->implements('InterfaceC')
``````php

























``````php

``````php

``````php



















``````php







``````php

``````php

``````php

``````php

``````php

``````php

``````php

``````php









``````php

```
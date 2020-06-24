# Directives

Think of `Endpoints` as a resource we can interact with.


By calling `Directives` before an `Endpoint` we may instruct it to behave a specific way.


```php
// Example using Directives on the Endpoint property 
$file->add('-')
    ->to()
    ->public()
    ->property('forbidden_letters')
```

## Available directives

* add
* include
* unique
* remove
* clear
* empty
* public
* protected
* private
* static
* assumeType

[Archetype\Traits\HasDirectiveDefaults](https://github.com/ajthinking/archetype/blob/master/src/Traits/HasDirectiveDefaults.php)
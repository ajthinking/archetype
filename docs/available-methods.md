&nbsp;
### PHPFileManipulator\Endpoints\PHP\IO
| method                     | parameters | description                         |
|----------------------------|------------|-------------------------------------|
| load($path)                |            | PHPFileManipulator\Endpoints\PHP\IO |
| fromString($code)          |            | PHPFileManipulator\Endpoints\PHP\IO |
| inputName()                |            | PHPFileManipulator\Endpoints\PHP\IO |
| inputDir()                 |            | PHPFileManipulator\Endpoints\PHP\IO |
| inputPath()                |            | PHPFileManipulator\Endpoints\PHP\IO |
| relativeInputPath()        |            | PHPFileManipulator\Endpoints\PHP\IO |
| save($outputPath)          |            | PHPFileManipulator\Endpoints\PHP\IO |
| outputPath()               |            | PHPFileManipulator\Endpoints\PHP\IO |
| debug()                    |            | PHPFileManipulator\Endpoints\PHP\IO |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\IO |
| parse()                    |            | PHPFileManipulator\Endpoints\PHP\IO |
| print()                    |            | PHPFileManipulator\Endpoints\PHP\IO |
| dd($method)                |            | PHPFileManipulator\Endpoints\PHP\IO |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\IO |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\IO |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\Template
| method                                        | parameters | description                               |
|-----------------------------------------------|------------|-------------------------------------------|
| fromTemplate($name, $path, $replacementPairs) |            | PHPFileManipulator\Endpoints\PHP\Template |
| supportedEndpointMethods()                    |            | PHPFileManipulator\Endpoints\PHP\Template |
| getEndpoints()                                |            | PHPFileManipulator\Endpoints\PHP\Template |
| ast()                                         |            | PHPFileManipulator\Endpoints\PHP\Template |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\FileQueryBuilder
| method                            | parameters | description                                       |
|-----------------------------------|------------|---------------------------------------------------|
| all()                             |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| in($directory)                    |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| where($arg1, $arg2, $arg3)        |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| andWhere($args)                   |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| get()                             |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| supportedEndpointMethods()        |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| getEndpoints()                    |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| ast()                             |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| equals($candidate, $expected)     |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| notEquals($candidate, $forbidden) |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| contains($candidate, $needle)     |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| inOperator($candidate, $haystack) |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| like($candidate, $like)           |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| matches($candidate, $regex)       |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| greaterThan($candidate, $length)  |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| lessThan($candidate, $length)     |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |
| count($candidate, $expected)      |            | PHPFileManipulator\Endpoints\PHP\FileQueryBuilder |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\AstQuery
| method                     | parameters | description                               |
|----------------------------|------------|-------------------------------------------|
| astQuery()                 |            | PHPFileManipulator\Endpoints\PHP\AstQuery |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\AstQuery |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\AstQuery |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\AstQuery |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\Reflection
| method                                  | parameters | description                                 |
|-----------------------------------------|------------|---------------------------------------------|
| export($argument, $return)              |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getName()                               |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isInternal()                            |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isUserDefined()                         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isAnonymous()                           |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isInstantiable()                        |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isCloneable()                           |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getFileName()                           |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getStartLine()                          |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getEndLine()                            |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getDocComment()                         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getConstructor()                        |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| hasMethod($name)                        |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getMethod($name)                        |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getMethods($filter)                     |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| hasProperty($name)                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getProperty($name)                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getProperties($filter)                  |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| hasConstant($name)                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getConstants()                          |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getReflectionConstants()                |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getConstant($name)                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getReflectionConstant($name)            |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getInterfaces()                         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getInterfaceNames()                     |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isInterface()                           |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getTraits()                             |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getTraitNames()                         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getTraitAliases()                       |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isTrait()                               |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isAbstract()                            |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isFinal()                               |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getModifiers()                          |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isInstance($object)                     |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| newInstance($args)                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| newInstanceWithoutConstructor()         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| newInstanceArgs($args)                  |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getParentClass()                        |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isSubclassOf($class)                    |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getStaticProperties()                   |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getStaticPropertyValue($name, $default) |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| setStaticPropertyValue($name, $value)   |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getDefaultProperties()                  |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isIterable()                            |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| isIterateable()                         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| implementsInterface($interface)         |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getExtension()                          |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getExtensionName()                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| inNamespace()                           |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getNamespaceName()                      |            | PHPFileManipulator\Endpoints\PHP\Reflection |
| getShortName()                          |            | PHPFileManipulator\Endpoints\PHP\Reflection |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\NamespaceResource
| method                     | parameters | description                                        |
|----------------------------|------------|----------------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| set($newNamespace)         |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| remove($_)                 |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| add($args)                 |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\NamespaceResource |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\Uses
| method                     | parameters | description                           |
|----------------------------|------------|---------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\Uses |
| set($newUseStatements)     |            | PHPFileManipulator\Endpoints\PHP\Uses |
| add($newUseStatements)     |            | PHPFileManipulator\Endpoints\PHP\Uses |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\Uses |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\Uses |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\Uses |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\Uses |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\Uses |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\ClassName
| method                     | parameters | description                                |
|----------------------------|------------|--------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| set($newClassName)         |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| add($args)                 |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\ClassName |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\ClassName |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\ClassExtends
| method                     | parameters | description                                   |
|----------------------------|------------|-----------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| set($newExtends)           |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| add($args)                 |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\ClassExtends |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\ClassImplements
| method                     | parameters | description                                      |
|----------------------------|------------|--------------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| set($newImplements)        |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| add($newImplements)        |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\ClassImplements |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\ClassMethods
| method                     | parameters | description                                   |
|----------------------------|------------|-----------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| add($methods)              |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| set($args)                 |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\ClassMethods |

&nbsp;
### PHPFileManipulator\Endpoints\PHP\ClassMethodNames
| method                     | parameters | description                                       |
|----------------------------|------------|---------------------------------------------------|
| get()                      |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| set($args)                 |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| add($args)                 |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| remove($args)              |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |
| ast()                      |            | PHPFileManipulator\Endpoints\PHP\ClassMethodNames |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\Fillable
| method                           | parameters | description                                   |
|----------------------------------|------------|-----------------------------------------------|
| get()                            |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| set($values)                     |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| items($requestedName)            |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| setItems($requestedName, $items) |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| add($args)                       |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| remove($args)                    |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| getPublicMethodsOnChild()        |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| supportedEndpointMethods()       |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| getEndpoints()                   |            | PHPFileManipulator\Endpoints\Laravel\Fillable |
| ast()                            |            | PHPFileManipulator\Endpoints\Laravel\Fillable |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\Hidden
| method                           | parameters | description                                 |
|----------------------------------|------------|---------------------------------------------|
| get()                            |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| set($values)                     |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| items($requestedName)            |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| setItems($requestedName, $items) |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| add($args)                       |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| remove($args)                    |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| getPublicMethodsOnChild()        |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| supportedEndpointMethods()       |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| getEndpoints()                   |            | PHPFileManipulator\Endpoints\Laravel\Hidden |
| ast()                            |            | PHPFileManipulator\Endpoints\Laravel\Hidden |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\HasOneMethods
| method                     | parameters | description                                        |
|----------------------------|------------|----------------------------------------------------|
| add($targets)              |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| get()                      |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| set($args)                 |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| remove($args)              |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |
| ast()                      |            | PHPFileManipulator\Endpoints\Laravel\HasOneMethods |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\HasManyMethods
| method                     | parameters | description                                         |
|----------------------------|------------|-----------------------------------------------------|
| add($targets)              |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| get()                      |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| set($args)                 |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| remove($args)              |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |
| ast()                      |            | PHPFileManipulator\Endpoints\Laravel\HasManyMethods |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\BelongsToMethods
| method                     | parameters | description                                           |
|----------------------------|------------|-------------------------------------------------------|
| add($targets)              |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| get()                      |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| set($args)                 |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| remove($args)              |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |
| ast()                      |            | PHPFileManipulator\Endpoints\Laravel\BelongsToMethods |

&nbsp;
### PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods
| method                     | parameters | description                                               |
|----------------------------|------------|-----------------------------------------------------------|
| add($targets)              |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| get()                      |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| set($args)                 |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| remove($args)              |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| getPublicMethodsOnChild()  |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| supportedEndpointMethods() |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| getEndpoints()             |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
| ast()                      |            | PHPFileManipulator\Endpoints\Laravel\BelongsToManyMethods |
|----------------------------|------------|-----------------------------------------------------------|
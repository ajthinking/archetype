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

## Command line reference

Every operation is an Artisan command named `archetype:<operation>`. The
`archetype` binary walks up from the working directory to find your
application's `artisan` file and forwards to it, so these are the same call:

```bash
./vendor/bin/archetype fillable app/Models/User.php
php artisan archetype:fillable app/Models/User.php
```

### The naming rule

`archetype` prints its operations in two halves, and the split is the rule:

* **An operation named after a `PHPFile` or `LaravelFile` endpoint is that
  endpoint.** Same arguments, same directives — as flags — same result. Give a
  value and it writes; give none and it reads.
* **An operation with a name of its own has no PHP equivalent** and belongs to
  the console alone.

Nothing is renamed on the way through. If you know the PHP API you already know
the commands.

| PHP | Command |
|---|---|
| `$file->property('table')` | `archetype property <target> table` |
| `$file->property('table', 'gdpr_users')` | `archetype property <target> table gdpr_users` |
| `$file->add()->property('fillable', 'nickname')` | `archetype property <target> fillable nickname --add` |
| `$file->remove()->property('table')` | `archetype property <target> table --remove` |
| `$file->empty()->property('fillable')` | `archetype property <target> fillable --empty` |
| `$file->private()->property('key', 'v')` | `archetype property <target> key v --private` |
| `$file->className()` | `archetype className <target>` |
| `$file->full()->className()` | `archetype className <target> --full` |
| `$file->add()->use([...])` | `archetype use <target> ... --add` |
| `$file->hasMany('Task')` | `archetype hasMany <target> Task` |

### Targets

Every operation but `make` and `apply` takes one target, which is any of:

| Target | Means |
|---|---|
| `app/Models/User.php` | that file |
| `App\Models\User` | that class, resolved to a path |
| `app/Models` | every PHP class under that directory |

A directory target can be narrowed:

| Option | Keeps only classes |
|---|---|
| `--extends=Model` | extending that class |
| `--implements=Auditable` | implementing that interface |
| `--uses-trait=SoftDeletes` | using that trait |
| `--matching=<regex>` | whose path matches |

These options are rejected on a single-file target rather than ignored.

### Options

| Option | Effect | On |
|---|---|---|
| `--json` | Emit JSON instead of the compact line format | every operation but `errors` |
| `--dry-run` | Show the diff without writing | every mutation |
| `--no-diff` | Suppress the diff | every mutation |

Directive flags — `--add`, `--remove`, `--empty`, `--clear`, `--full`,
`--public`, `--protected`, `--private`, `--static` — appear only on the
operations whose endpoint honours them.

### Exit codes and statuses

| Status | Meaning | Exit |
|---|---|---|
| `OK <file> <detail>` | Changed and saved | 0 |
| `DRY <file> <detail>` | Would change; nothing written | 0 |
| `SKIP <file> <detail>` | Already in the desired state | 0 |
| `ERR <file> <detail>` | Could not do what was asked | 1 |

A mutation that matches nothing reports `ERR`, never `OK`. That is what makes it
safe not to read the file back.

### Reading an endpoint

With no value, an endpoint command answers with its value. A single file answers
with the value alone, so it can be piped; a directory answers with one
`path value` line per file.

```bash
$ archetype fillable app/Models/User.php
["name","email","password"]

$ archetype table app/Models/User.php
gdpr_users

$ archetype className app/Models/User.php --full
App\Models\User

$ archetype fillable app/Models
app/Models/User.php ["name","email","password"]
app/Models/Project.php ["name"]
```

Scalars print raw; arrays and objects print as compact JSON. `--json` gives the
typed value.

### The endpoints

```bash
# PHPFile
archetype property      <target> <name> [<value>]   # --add --remove --empty --clear --public --protected --private --static
archetype className     <target> [<NewName>]        # --full
archetype extends       <target> [<Class>]
archetype implements    <target> [<Interface>...]   # --add
archetype namespace     <target> [<Namespace>]      # --remove
archetype use           <target> [<FQCN>...]        # --add
archetype useTrait      <target> [<Trait>...]       # --add
archetype classConstant <target> <NAME> [<value>]   # --add --remove --empty --clear
archetype methodNames   <target>
archetype make          <name>                      # --file --extends= --implements= --trait= --force
archetype errors

# LaravelFile model properties
archetype fillable   <target> [<value>]             # --add --remove --empty --clear
archetype hidden     <target> [<value>]
archetype visible    <target> [<value>]
archetype guarded    <target> [<value>]
archetype unguarded  <target> [<value>]
archetype casts      <target> [<value>]
archetype dates      <target> [<value>]
archetype table      <target> [<value>]
archetype connection <target> [<value>]
archetype timestamps <target> [<value>]

# LaravelFile relationships
archetype hasOne        <target> <Related>
archetype hasMany       <target> <Related>
archetype belongsTo     <target> <Related>
archetype belongsToMany <target> <Related>
```

Without `--add`, `use`, `useTrait` and `implements` replace the list wholesale,
exactly as the endpoints do. With it they append.

`useTrait`, `implements` and `extends` add the import when given a fully
qualified name, because a name used without one is never valid PHP.
`--no-import` leaves that to you.

With nothing but a related class, the four relationship commands call the
endpoint, so `archetype hasMany <target> Task` and `$file->hasMany('Task')`
produce byte-identical output. Given options the endpoint cannot express, the
method is generated instead:

```bash
archetype belongsTo     <target> User --name=owner --foreign-key=owner_id
archetype belongsToMany <target> Label --table=label_project --with-pivot=sort,note --with-timestamps
archetype hasMany       <target> Task --foreign-key=project_id --local-key=uuid
```

`archetype casts` writes the `$casts` property. On a model that declares the
`casts()` method Laravel 11 generates, it refuses rather than leaving the model
with two casting mechanisms, and points at `set-array-key` instead.

### The console's own operations

These have no PHP equivalent.

#### inspect — structure, without method bodies
```bash
archetype inspect app/Models/User.php
archetype inspect app/Models/User.php props relations
```
```
app/Models/User.php
class App\Models\User extends Authenticatable
uses HasApiTokens, HasFactory, Notifiable
import Illuminate\Foundation\Auth\User as Authenticatable
prop protected $fillable = ["name","email","password"]
fn public posts() [4 lines]
rel posts hasMany Post
```

Sections: `meta`, `traits`, `uses`, `consts`, `cases`, `props`, `methods`,
`relations`.

#### show — the source of one method
```bash
archetype show app/Http/Requests/StoreTaskRequest.php rules
```

`inspect` deliberately leaves method bodies out; this is how you get one.

#### find — which files are there, and what they are
```bash
archetype find app
archetype find app --type=models
archetype find --type=migrations
archetype find app --extends=FormRequest
archetype find app --matching='Http/Controllers'
```

`--type` is one of `all`, `models`, `controllers`, `providers`, `migrations`.
The class types use reflection, so they only see classes the application can
autoload; the other filters read the syntax tree and work on anything that
parses.

#### set-array-key — the array a method returns
```bash
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules due_at 'nullable|date'
archetype set-array-key app/Http/Resources/TaskResource.php toArray budget '$this->budget_cents'
archetype set-array-key app/Models/Project.php casts archived boolean
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules tags "['array', 'max:5']"
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules title --remove
```

This reaches `rules()`, `toArray()`, `casts()`, `definition()` and everything
else of that shape — the array a method returns directly, never one returned
from a closure nested inside it.

A bare word is a string, so `nullable|date` is a validation rule rather than a
bitwise or. Brackets, quotes, `$variables`, calls, `Class::constants`, numbers
and booleans are read as PHP.

#### add-case — an enum case
```bash
archetype add-case app/Enums/ProjectStatus.php OnHold on_hold
archetype add-case app/Enums/Suit.php Spades          # pure enum, no backing value
```

New cases are added after the ones already there.

#### The method operations
```bash
archetype add-method app/Models/Project.php \
    --code='public function scopeActive($query) { return $query->where("active", true); }'
archetype replace-method app/Models/Project.php isActive \
    --code='public function isActive(): bool { return $this->active; }'
archetype remove-method app/Models/Project.php isActive
```

Methods can be added to a class, enum, interface or trait, and are appended
after the methods already there.

#### The relations the endpoints do not have
```bash
archetype hasOneThrough  <target> <Related> --through=Task
archetype hasManyThrough <target> <Related> --through=Task
archetype morphOne       <target> <Related> --morph-name=commentable
archetype morphMany      <target> <Related> --morph-name=commentable
archetype morphTo        <target>           [--morph-name=commentable]
archetype morphToMany    <target> <Related> --morph-name=taggable
archetype morphedByMany  <target> <Related> --morph-name=taggable
```

#### apply — several operations in one call
```bash
archetype apply operations.txt
archetype apply < operations.txt
```

One operation per line, `#` for comments, the `archetype:` prefix optional:

```text
# what this change needs
fillable   app/Models/Project.php budget_cents --add
casts      app/Models/Project.php '{"budget_cents":"integer"}' --add
hasMany    app/Models/Project.php Task
```

Each operation keeps its own verification, diff and exit status. `apply` exits
non-zero if any of them failed, and `--stop-on-failure` stops at the first.

### What the console will not do

The endpoints address `class` declarations, so `property`, the model
properties, `classConstant`, `implements`, `useTrait`, `extends`, `className`
and the relationships work on classes only. On an enum, interface or trait they
refuse and write nothing, rather than writing the part they can and reporting
success:

```
$ archetype implements app/Enums/Status.php 'App\Contracts\HasColor' --add
ERR app/Enums/Status.php archetype:implements only works on classes, and this is an enum
```

`inspect`, `show`, `find`, the method operations, `add-case` and `set-array-key`
have no such limit — they read or write the declaration whatever it is.

### JSON

Every operation but `errors` takes `--json`:

```bash
archetype fillable app/Models/User.php nickname --add --json
```
```json
{"ok":true,"dryRun":false,"changed":1,"skipped":0,"failed":0,"results":[{"file":"app/Models/User.php","status":"changed","detail":"$fillable added to","diff":"@@ 24 @@\n+         'nickname',\n      ];"}]}
```

A read answers with `{"file":"...","value":...}`, or `{"values":{...},"count":n}`
for a directory. An error answers with `{"ok":false,"error":"..."}` and exit
code 1.

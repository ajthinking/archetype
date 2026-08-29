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
./vendor/bin/archetype inspect app/Models/User.php
php artisan archetype:inspect app/Models/User.php
```

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

### Options every operation takes

| Option | Effect |
|---|---|
| `--json` | Emit JSON instead of the compact line format |

### Options every mutation takes

| Option | Effect |
|---|---|
| `--dry-run` | Show the diff without writing |
| `--no-diff` | Suppress the diff |

### Exit codes and statuses

| Status | Meaning | Exit |
|---|---|---|
| `OK <file> <detail>` | Changed and saved | 0 |
| `DRY <file> <detail>` | Would change; nothing written | 0 |
| `SKIP <file> <detail>` | Already in the desired state | 0 |
| `ERR <file> <detail>` | Could not do what was asked | 1 |

A mutation that matches nothing reports `ERR`, never `OK`. That is what makes it
safe not to read the file back.

### Reading

#### Summarise a file
```bash
archetype inspect app/Models/User.php
```
```
app/Models/User.php
class App\Models\User extends Authenticatable
uses HasApiTokens, HasFactory, Notifiable
import Illuminate\Foundation\Auth\User as Authenticatable
prop protected $fillable = ["name","email","password"]
prop protected $casts = {"email_verified_at":"datetime"}
fn public posts() [4 lines]
rel posts hasMany Post
```

Limit it to the sections you need — `meta`, `traits`, `uses`, `consts`, `cases`,
`props`, `methods`, `relations`:

```bash
archetype inspect app/Models/User.php props relations
```

#### Print one method
```bash
archetype show app/Http/Requests/StoreTaskRequest.php rules
```

`inspect` deliberately leaves method bodies out; this is how you get one.

#### Find files
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

#### List files that do not parse
```bash
archetype errors
```

### Creating

```bash
archetype make 'App\Services\Billing'
archetype make app/Services/Billing.php
archetype make 'App\Models\Invoice' \
    --extends='Illuminate\Database\Eloquent\Model' \
    --implements='App\Contracts\Payable' \
    --trait='Illuminate\Database\Eloquent\Factories\HasFactory'
archetype make app/helpers.php --file
```

Refuses to overwrite an existing file unless given `--force`.

### Properties

```bash
archetype set-property app/Models/User.php table gdpr_users
archetype set-property app/Models/User.php with '["profile","posts"]'
archetype set-property app/Models/User.php perPage 25 --visibility=public
archetype set-property app/Models/User.php connection          # no default value

archetype add-to-property app/Models/User.php fillable nickname avatar
archetype empty-property app/Models/User.php fillable
archetype remove-property app/Models/User.php hidden
```

Values are read as JSON when they are valid JSON, and as a plain string
otherwise. Visibility is left as it is unless `--visibility` says otherwise.

### Eloquent

```bash
archetype set-casts app/Models/User.php archived_at=datetime status=Status::class

archetype add-relation app/Models/Project.php hasMany Task
archetype add-relation app/Models/Project.php belongsTo User --name=owner --foreign-key=owner_id
archetype add-relation app/Models/Project.php belongsToMany Label \
    --table=label_project --with-pivot=sort,note --with-timestamps
archetype add-relation app/Models/Project.php morphMany Comment --morph-name=commentable
archetype add-relation app/Models/Project.php hasManyThrough Comment --through=Task
```

`set-casts` writes to whichever mechanism the model already uses — the `casts()`
method Laravel 11 generates, or the `$casts` property — rather than adding a
second one beside it.

`add-relation` covers all eleven relation types: `hasOne`, `hasMany`,
`belongsTo`, `belongsToMany`, `hasOneThrough`, `hasManyThrough`, `morphOne`,
`morphMany`, `morphTo`, `morphToMany`, `morphedByMany`. The related class is
imported when it needs to be.

### Arrays returned from methods

```bash
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules due_at 'nullable|date'
archetype set-array-key app/Http/Resources/TaskResource.php toArray budget '$this->budget_cents'
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules tags "['array', 'max:5']"
archetype set-array-key app/Http/Requests/StoreTaskRequest.php rules title --remove
archetype set-array-key app/Providers/AppServiceProvider.php policies ignored Policy::class --append
```

This reaches `rules()`, `toArray()`, `casts()`, `definition()` and everything
else of that shape — the array a method returns directly, never one returned
from a closure nested inside it.

A bare word is a string, so `nullable|date` is a validation rule rather than a
bitwise or. Brackets, quotes, `$variables`, calls, `Class::constants`, numbers
and booleans are read as PHP.

### Structure

```bash
archetype add-use app/Models/User.php 'App\Contracts\Auditable' 'Illuminate\Support\Str'
archetype remove-use app/Models/User.php 'Illuminate\Support\Str'
archetype add-trait app/Models/User.php 'Illuminate\Database\Eloquent\SoftDeletes'
archetype add-implements app/Models/User.php 'App\Contracts\Auditable'
archetype set-extends app/Models/User.php 'Illuminate\Database\Eloquent\Model'
archetype set-namespace app/Models/User.php 'App\Domain\Models'
archetype rename-class app/Models/User.php Account
```

`add-trait`, `add-implements` and `set-extends` add the import too, since a name
used without one is never valid PHP.

`rename-class` renames the declaration only. It does not move the file or update
references elsewhere.

### Constants and enum cases

```bash
archetype set-const app/Models/User.php HOME /dashboard
archetype remove-const app/Models/User.php HOME

archetype add-case app/Enums/ProjectStatus.php OnHold on_hold
archetype add-case app/Enums/Suit.php Spades          # pure enum, no backing value
```

Constants work on classes, interfaces, enums and traits. New enum cases are
added after the ones already there.

### Methods

```bash
archetype add-method app/Models/Project.php \
    --code='public function scopeActive($query) { return $query->where("active", true); }'
archetype replace-method app/Models/Project.php isActive \
    --code='public function isActive(): bool { return $this->active; }'
archetype remove-method app/Models/Project.php isActive
```

Methods can be added to a class, enum, interface or trait, and are appended
after the methods already there.

### Several operations in one call

```bash
archetype apply operations.txt
archetype apply < operations.txt
```

One operation per line, `#` for comments, the `archetype:` prefix optional:

```text
# what this change needs
add-to-property app/Models/Project.php fillable budget_cents
set-casts app/Models/Project.php budget_cents=integer
add-relation app/Models/Project.php hasMany Task
```

Each operation keeps its own verification, diff and exit status. `apply` exits
non-zero if any of them failed, and `--stop-on-failure` stops at the first.

### JSON

Every operation takes `--json`:

```bash
archetype add-to-property app/Models/User.php fillable nickname --json
```
```json
{"ok":true,"dryRun":false,"changed":1,"skipped":0,"failed":0,"results":[{"file":"app/Models/User.php","status":"changed","detail":"$fillable +1","diff":"@@ 24 @@\n+         'nickname',\n      ];"}]}
```

An error answers with `{"ok":false,"error":"..."}` and exit code 1.

## API

### Make an empty file
```php example
PHPFile::make()->file('dummy.php')
	->render()
```

```php
<?php
```


### Make a class file
```php example
PHPFile::make()->class('Car')
	->render()
```

<details><summary>Output</summary>

```php
<?php

namespace App;

class Car
{
}
```


</details>

### Load an existing file
```php
PHPFile::load('app/Models/User.php')
	->render()
```
<details><summary>Output</summary>

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

</details>

### Get class name
```php example
PHPFile::load('app/Models/User.php')
	->className();
```

```string
User
```


### Change class name
```php example
PHPFile::make()->class('Dumb')
	->className('Dumber')
	->className()
```

```string
Dumber
```
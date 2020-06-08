<a href='https://github.com/ajthinking/archetype/blob/master/src/Endpoints/SyntacticSweetener.php'>![Archetype\Endpoints\SyntacticSweetener](https://img.shields.io/badge/-Archetype\Endpoints\SyntacticSweetener-blue)</a>
```php
use LaravelFile as Assistant;

// Make syntax more 'readable' with intermediate words
Assistant::please()->make()->model('App\Car')
    ->and()->add('nickname')->to()->fillable()
    ->also()->add()->a()->trait('HasNickname')
    ->then()->save()
    ->thanks();

// Supported words
$words = [
    'a',
    'also',
    'and',
    'epic',
    'from',
    'have',
    'it',
    'make',
    'please',
    'should',
    'thanks',
    'then',
    'to',
];
```
<hr>
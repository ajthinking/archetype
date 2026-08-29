# Changelog

All notable changes to `ajthinking/archetype` are documented here.

This project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.0] - 2026-08-29

Adds a command line to Archetype. The PHP API is untouched — no endpoint, no
printer and no query builder changes. Everything new lives in `src/Console`.

### Added

- **A command line.** Each operation is an Artisan command under `archetype:`,
  plus an `archetype` binary that finds the application and forwards to it. Run
  `archetype` with no arguments for the list, or see the
  [reference](docs.md#command-line-reference).

- **An operation named after an endpoint is that endpoint.** Same arguments, same
  directives as flags, same result — `archetype property <target> fillable
  nickname --add` is `$file->add()->property('fillable', 'nickname')`. Give a
  value and it writes, give none and it reads. Endpoint commands: `property`,
  `className`, `extends`, `implements`, `namespace`, `use`, `useTrait`,
  `classConstant`, `methodNames`, `make`, the ten `LaravelFile` model properties
  (`fillable`, `hidden`, `visible`, `guarded`, `unguarded`, `casts`, `dates`,
  `table`, `connection`, `timestamps`) and the four relationships (`hasOne`,
  `hasMany`, `belongsTo`, `belongsToMany`).

- **Operations with names of their own, which have no PHP equivalent**:
  `inspect`, `show`, `find`, `set-array-key`, `add-case`, `add-method`,
  `replace-method`, `remove-method`, `apply`, and the seven relationship types
  `LaravelFile` does not cover (`hasOneThrough`, `hasManyThrough`, `morphOne`,
  `morphMany`, `morphTo`, `morphToMany`, `morphedByMany`).

- Every operation takes a single target, which is a path, a class name, or a
  directory — where a directory means every class beneath it, narrowed with
  `--extends`, `--implements`, `--uses-trait` or `--matching`.
- Every operation but `errors` takes `--json`.
- Every mutation re-renders the file and compares before reporting. One that
  matched nothing exits non-zero rather than reporting a success that wrote
  nothing; one whose change is already present reports `SKIP`, which makes the
  operations safe to repeat.
- Every mutation answers with a diff of what it changed, and takes `--dry-run`
  to show that diff without writing.
- An operation that cannot act on the construct it was pointed at refuses before
  writing anything, rather than writing the part it can. `implements`,
  `useTrait` and `extends` import a name before using it, so a half-done change
  would otherwise look like a whole one.
- `archetype apply` runs a script of operations in one invocation, reading a file
  or standard input.
- `set-array-key` edits the array a method returns, which is where `rules()`,
  `toArray()`, `casts()` and `definition()` keep their contents.
- `archetype casts` refuses to write `$casts` on a model that declares the
  `casts()` method Laravel 11 generates, rather than leaving it with two casting
  mechanisms, and points at `set-array-key` instead.

### Known limits

- The endpoints address `class` declarations, so the endpoint-named operations
  work on classes only. On an enum, interface or trait they refuse and write
  nothing. `inspect`, `show`, `find`, the method operations, `add-case` and
  `set-array-key` have no such limit.

## [2.0.1] - 2026-08-25

Maintenance only. No API changes, and nothing here can break existing usage.

### Added

- Support for PHP 8.1 through 8.4, and for Laravel 10, 11, 12 and 13 — now
  verified in CI across twelve combinations rather than assumed. Laravel 13 is
  included because `statamic/cms` 6 already allows it.
- A `php` requirement (`^8.1`) in `composer.json`. The package never declared
  one, so Composer could not warn anybody. 8.1 is the floor the code actually
  needs and the whole range CI exercises; the upper bound is deliberate, because
  PHP 9 removes dynamic properties and the AST node-identity mechanism still
  relies on them.
- `LICENSE.md`. The package has always been MIT; the file was missing.
- This changelog.
- `.github/dependabot.yml`, so dependency updates are configured rather than
  running on defaults.

### Changed

- The test suite now runs against a pinned application skeleton in
  `tests/fixtures/laravel` instead of copying one out of `vendor/laravel/laravel`
  at runtime. Laravel 11 removed most of the files the suite relied on
  (`app/Console/Kernel.php`, `app/Exceptions/Handler.php`, `RouteServiceProvider`
  and the middleware), which made the old approach untestable on current Laravel.
  `laravel/laravel` is no longer a dev dependency.
- CI runs on `actions/checkout@v4` and `actions/cache@v4`, and no longer uses the
  `::set-output` command that GitHub has disabled.
- `phpunit.xml` migrated to the PHPUnit 10+ schema.
- `Archetype\Tests\` moved out of the production autoloader into `autoload-dev`.
- `minimum-stability` is now `stable`.

### Fixed

- Seven implicit-nullable parameters that raised deprecation notices on PHP 8.4
  (`PHPFile::namespace()`, `Namespace_::namespace()`, `FileInput::load()`,
  `InputInterface::load()`, `EndpointProvider::__construct()`,
  `PHPParserPropertyMap::propertyMap()` and `ASTQueryBuilder::traverseIntoClass()`).
- `getReflection()` now catches `Throwable` rather than `Exception`. A file whose
  parent class or trait cannot be autoloaded raises an `Error`, which previously
  escaped and killed the entire query instead of skipping that one file. This
  affects `LaravelFile::models()`, `controllers()` and `serviceProviders()`.
- `tests/Pest.php` no longer defines a global `context()` helper. Laravel 11 ships
  its own `context()`, and the collision brought the whole suite down before a
  single test could run.

## [2.0.0] - 2024-05-11

### Changed

- **Breaking:** upgraded to `nikic/php-parser` ^5.0. Thanks to @jasonvarga (#85).

## [1.1.5] - 2022-08-24

Last release of the 1.x line, which requires `nikic/php-parser` ^4.11.

1.x cannot be installed alongside anything that needs php-parser 5 — including
Pest 3 and newer. If Composer refuses to resolve `ajthinking/archetype`, upgrade
to 2.x.

[Unreleased]: https://github.com/ajthinking/archetype/compare/v2.1.0...HEAD
[2.1.0]: https://github.com/ajthinking/archetype/compare/v2.0.1...v2.1.0
[2.0.1]: https://github.com/ajthinking/archetype/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/ajthinking/archetype/compare/v1.1.5...v2.0.0
[1.1.5]: https://github.com/ajthinking/archetype/releases/tag/v1.1.5

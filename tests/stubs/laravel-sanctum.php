<?php

namespace Laravel\Sanctum;

/**
 * The fixture application skeleton in tests/fixtures/laravel is a verbatim copy
 * of a Laravel skeleton, and its User model uses this trait. Reflection-based
 * scopes such as LaravelFile::models() load those classes for real, so the
 * trait has to exist.
 *
 * Stubbing it here keeps the fixture faithful without pulling laravel/sanctum
 * — and its per-Laravel-version constraints — into the test matrix.
 */
trait HasApiTokens
{
}

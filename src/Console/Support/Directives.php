<?php

namespace Archetype\Console\Support;

/**
 * The PHP API's directives, as the console names them.
 *
 * These live on a class rather than on the trait that uses them because
 * constants in traits are PHP 8.2, and this package supports 8.1.
 */
class Directives
{
    /** directive method => flag description */
    const ALL = [
        'add' => 'Add to what is there instead of replacing it',
        'remove' => 'Remove it',
        'clear' => 'Clear the default value, keeping the declaration',
        'empty' => 'Empty it, keeping the declaration',
        'full' => 'Answer with the fully qualified name',
        'public' => 'Declare it public',
        'protected' => 'Declare it protected',
        'private' => 'Declare it private',
        'static' => 'Declare it static',
    ];

    /** The directives that make an operation a write rather than a read. */
    const WRITING = ['add', 'remove', 'clear', 'empty'];

    /** The directives that choose a visibility. */
    const VISIBILITY = ['public', 'protected', 'private'];
}

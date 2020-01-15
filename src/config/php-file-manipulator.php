<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default input, output and debug disks
    |--------------------------------------------------------------------------
    */
    'roots' => [
        'input' => [
            'driver' => 'local',
            'root' => base_path(),
        ],
        'output' => [
            'driver' => 'local',
            'root' => base_path(),
        ],
        'debug' => [
            'driver' => 'local',
            'root' => storage_path('php-file-manipulator/preview'),
        ],                                
    ],

    /*
    |--------------------------------------------------------------------------
    | The QueryBuilder will ignore these files or folders
    |--------------------------------------------------------------------------
    */    
    'ignored_paths' => [
        'node_modules',
        'vendor',
        '_ide_helper.php',
        '.git',
    ],

    /*
    |--------------------------------------------------------------------------
    | The Snippet class will search for snippets here
    |--------------------------------------------------------------------------
    */    
    'snippet_search_paths' => [
        'some/path',
    ],    
];
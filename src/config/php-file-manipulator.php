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
            'root' => storage_path('php-file-manipulator/debug'),
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
    | The Snippet class will search for snippets here (relative base path)
    |--------------------------------------------------------------------------
    */    
    'snippets_path' => 'resources/snippets',

    /*
    |--------------------------------------------------------------------------
    | The Tags used by the File instances as defaults
    |--------------------------------------------------------------------------
    */
    'tags' => [
        //
    ],
];
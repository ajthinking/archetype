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
            'root' => storage_path('archetype/debug'),
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
    | Project file structure
    |--------------------------------------------------------------------------
    */
    'locations' => [
        'namespace_map' => [
            'app' => 'App',
        ],
                
        // Application root/default namespace
        'app_path' => 'app',
        'app_namespace' => 'App',
        
        // PHP
        'file_root' => '',
        'class_root' => '',

        // Laravel
        'commands_root' => 'app/HTTP/Controllers',
        'controllers_root' => 'app/HTTP/Controllers',
        'factories_root' => 'database/factories',
        'migrations_root' => 'database/migrations',
        'models_root' => 'app/Models',
    ],
];

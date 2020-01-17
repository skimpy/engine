<?php

return [

    'default' => env('SKIMPY_DB_CONNECTION', 'skimpy'),

    'connections' => [
        'skimpy' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => app()->runningInConsole() ? __DIR__ . '/../../../../database/skimpy.sqlite' : base_path('database/skimpy.sqlite'),
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ]
    ],
];

<?php

return [
    'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_PUBLIC_DSN')),
    // Capture release as git sha.
    'release' => null, // Look template\App\Providers\AppServiceProvider;
    'breadcrumbs' => [
        // Capture bindings on SQL queries logged in breadcrumbs.
        'sql_bindings' => true,
    ],
];

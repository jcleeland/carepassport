<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'Life & Care Passport'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'storage_path' => env('STORAGE_PATH', base_path('storage')),
    'upload_max_mb' => (int) env('UPLOAD_MAX_MB', 10),
    'session_name' => env('SESSION_NAME', 'care_passport_session'),
];

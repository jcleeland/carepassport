<?php

declare(strict_types=1);

return [
    'max_upload_bytes' => max(1, (int) env('UPLOAD_MAX_MB', 5)) * 1024 * 1024,
    'processed_width' => 1200,
    'processed_height' => 1200,
    'storage_directory' => 'storage/app/portraits',
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/webp'],
];

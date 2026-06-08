<?php

// Vercel runs PHP from a read-only filesystem except for /tmp.
// Prepare a writable storage tree there before booting Laravel.
$storage = '/tmp/storage';
$dirs = [
    $storage,
    $storage . '/app',
    $storage . '/app/public',
    $storage . '/framework',
    $storage . '/framework/cache',
    $storage . '/framework/cache/data',
    $storage . '/framework/sessions',
    $storage . '/framework/testing',
    $storage . '/framework/views',
    $storage . '/logs',
];
foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

putenv('APP_STORAGE_PATH=' . $storage);
$_ENV['APP_STORAGE_PATH'] = $storage;
$_SERVER['APP_STORAGE_PATH'] = $storage;

require __DIR__ . '/../public/index.php';

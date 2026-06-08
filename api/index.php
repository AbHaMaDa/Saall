<?php

// Vercel runs PHP from a read-only filesystem except for /tmp.
// Prepare writable storage and bootstrap-cache trees there before booting Laravel.
$storage = '/tmp/storage';
$bootstrap = '/tmp/bootstrap';
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
    $bootstrap,
    $bootstrap . '/cache',
];
foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Seed /tmp/bootstrap/cache from the deployed (read-only) bootstrap/cache if present,
// so Laravel finds packages.php / services.php without re-discovering on every cold start.
$srcCache = __DIR__ . '/../bootstrap/cache';
if (is_dir($srcCache)) {
    foreach (glob($srcCache . '/*.php') ?: [] as $file) {
        $dest = $bootstrap . '/cache/' . basename($file);
        if (! file_exists($dest)) {
            @copy($file, $dest);
        }
    }
}

putenv('APP_STORAGE_PATH=' . $storage);
$_ENV['APP_STORAGE_PATH'] = $storage;
$_SERVER['APP_STORAGE_PATH'] = $storage;

putenv('APP_BOOTSTRAP_PATH=' . $bootstrap);
$_ENV['APP_BOOTSTRAP_PATH'] = $bootstrap;
$_SERVER['APP_BOOTSTRAP_PATH'] = $bootstrap;

require __DIR__ . '/../public/index.php';

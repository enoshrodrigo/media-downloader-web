<?php
// Load environment variables from .env if available
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        $_ENV[$name] = $value;
    }
}

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'your_username');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'your_password');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'media_downloader');

// Directory paths
define('MEDIA_VIDEOS_DIR', __DIR__ . '/../' . ($_ENV['MEDIA_VIDEOS_DIR'] ?? 'media/videos/'));
define('MEDIA_MUSIC_DIR', __DIR__ . '/../' . ($_ENV['MEDIA_MUSIC_DIR'] ?? 'media/music/'));

// Base URL
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost/media-downloader/');

// Other constants
define('MAX_FILE_SIZE', isset($_ENV['MAX_FILE_SIZE']) ? (int)$_ENV['MAX_FILE_SIZE'] : 10485760); // 10 MB

define('ALLOWED_FILE_TYPES', isset($_ENV['ALLOWED_FILE_TYPES'])
    ? array_map('trim', explode(',', $_ENV['ALLOWED_FILE_TYPES']))
    : ['video/mp4', 'audio/mpeg', 'image/jpeg', 'image/png']);

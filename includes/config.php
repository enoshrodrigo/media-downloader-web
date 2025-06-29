<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'media_downloader');

// Directory paths
define('MEDIA_VIDEOS_DIR', __DIR__ . '/../media/videos/');
define('MEDIA_MUSIC_DIR', __DIR__ . '/../media/music/');

// Base URL
define('BASE_URL', 'http://localhost/media-downloader/');

// Other constants
define('MAX_FILE_SIZE', 10485760); // 10 MB
define('ALLOWED_FILE_TYPES', ['video/mp4', 'audio/mpeg', 'image/jpeg', 'image/png']);
?>
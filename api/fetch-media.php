<?php 
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/MediaHandler.php';

header('Content-Type: application/json');

$mediaHandler = new MediaHandler(MEDIA_VIDEOS_DIR, MEDIA_MUSIC_DIR);
$videos = $mediaHandler->getVideos();
$music = $mediaHandler->getMusic();

$response = [
    'videos' => $videos,
    'music' => $music
];

echo json_encode($response);
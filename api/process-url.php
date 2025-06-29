<?php
 
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/MediaHandler.php';
require_once '../includes/DownloadProcessor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create necessary objects
    $mediaHandler = new MediaHandler(MEDIA_VIDEOS_DIR, MEDIA_MUSIC_DIR);
    $downloadProcessor = new DownloadProcessor($mediaHandler);
    
    // Get URL from POST data (supporting both form submission and JSON)
    $postData = json_decode(file_get_contents('php://input'), true);
    $url = isset($postData['url']) ? $postData['url'] : (isset($_POST['url']) ? $_POST['url'] : '');
    $url = filter_var(trim($url), FILTER_SANITIZE_URL);

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        try {
            $result = $downloadProcessor->processUrl($url);
            echo json_encode(['success' => true, 'message' => 'Download initiated.', 'file' => basename($result)]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid URL provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
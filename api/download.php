<?php
 

require_once '../includes/config.php';
require_once '../includes/DownloadProcessor.php';

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $downloadProcessor = new DownloadProcessor($config);

    if ($downloadProcessor->isValidFile($file)) {
        $downloadProcessor->downloadFile($file);
    } else {
        echo json_encode(['error' => 'Invalid file request.']);
    }
} else {
    echo json_encode(['error' => 'No file specified.']);
}
?>
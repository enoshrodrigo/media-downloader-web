<?php
// This file contains utility functions used throughout the application for various tasks, such as file handling and media processing.

function getMediaFiles($directory) {
    $files = array_diff(scandir($directory), array('..', '.'));
    return array_values($files);
}

function downloadFile($filePath) {
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        return false;
    }
}

function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function sanitizeFileName($fileName) {
    return preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
}
?>
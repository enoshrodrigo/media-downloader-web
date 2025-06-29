<?php
 
class DownloadProcessor {
    private $mediaHandler;
    private $videosDir;
    private $musicDir;

    public function __construct($mediaHandler) {
        $this->mediaHandler = $mediaHandler;
        $this->videosDir = MEDIA_VIDEOS_DIR;
        $this->musicDir = MEDIA_MUSIC_DIR;
    }

    public function processDownloadRequest($filePath) {
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
            throw new Exception("File not found.");
        }
    }

    public function processUrl($url) {
        // Validate the URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL.");
        }

        // Extract file extension from URL
        $pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
        $ext = strtolower($pathInfo['extension'] ?? '');
        
        // Define allowed file types
        $videoTypes = ['mp4', 'webm', 'mov', 'avi'];
        $audioTypes = ['mp3', 'wav', 'ogg'];
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        $allowedTypes = array_merge($videoTypes, $audioTypes, $imageTypes);
        
        if (!in_array($ext, $allowedTypes)) {
            throw new Exception("Unsupported file type.");
        }
        
        // Determine target directory based on file type
        if (in_array($ext, $videoTypes)) {
            $targetDir = $this->videosDir;
        } elseif (in_array($ext, $audioTypes)) {
            $targetDir = $this->musicDir;
        } else {
            // For images, store in videos directory
            $targetDir = $this->videosDir;
        }
        
        // Create target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Generate unique filename
        $filename = uniqid() . '.' . $ext;
        $targetPath = $targetDir . $filename;
        
        // Download the file
        $fileContent = file_get_contents($url);
        if ($fileContent === false) {
            throw new Exception("Failed to download file from URL.");
        }
        
        // Save the file
        if (file_put_contents($targetPath, $fileContent) === false) {
            throw new Exception("Failed to save file.");
        }
        
        return $targetPath;
    }
    
    public function isValidFile($filename) {
        // Check if file exists in either videos or music directory
        return file_exists($this->videosDir . $filename) || 
               file_exists($this->musicDir . $filename);
    }
    
    public function downloadFile($filename) {
        // Determine the file path
        if (file_exists($this->videosDir . $filename)) {
            $filePath = $this->videosDir . $filename;
        } elseif (file_exists($this->musicDir . $filename)) {
            $filePath = $this->musicDir . $filename;
        } else {
            throw new Exception("File not found.");
        }
        
        $this->processDownloadRequest($filePath);
    }
}
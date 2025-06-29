<?php
 
class MediaHandler {
    private $videoDir;
    private $musicDir;

    public function __construct($videoDir, $musicDir) {
        $this->videoDir = rtrim($videoDir, '/') . '/';
        $this->musicDir = rtrim($musicDir, '/') . '/';
        
        // Create directories if they don't exist
        if (!is_dir($this->videoDir)) {
            mkdir($this->videoDir, 0755, true);
        }
        
        if (!is_dir($this->musicDir)) {
            mkdir($this->musicDir, 0755, true);
        }
    }

    public function getVideos() {
        return $this->getMediaFiles($this->videoDir);
    }

    public function getMusic() {
        return $this->getMediaFiles($this->musicDir);
    }

    private function getMediaFiles($directory) {
        if (!is_dir($directory)) {
            return []; // Return empty array if directory doesn't exist
        }
        
        $files = array_diff(scandir($directory), array('..', '.'));
        $mediaFiles = [];

        foreach ($files as $file) {
            $filePath = $directory . $file;
            if (is_file($filePath)) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $title = pathinfo($file, PATHINFO_FILENAME);
                
                $mediaFiles[] = [
                    'name' => $file,
                    'title' => $title,
                    'description' => 'File type: ' . strtoupper($extension),
                    'path' => $filePath,
                    'thumbnail' => $this->getThumbnail($filePath, $extension),
                    'download_link' => 'media/' . ($directory === $this->videoDir ? 'videos/' : 'music/') . $file
                ];
            }
        }

        return $mediaFiles;
    }
    
    private function getThumbnail($filePath, $extension) {
        // Default thumbnail based on file type
        if (in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm'])) {
            return 'assets/images/video-thumbnail.png';
        } elseif (in_array(strtolower($extension), ['mp3', 'wav', 'ogg'])) {
            return 'assets/images/audio-thumbnail.png';
        } else {
            return 'assets/images/file-thumbnail.png';
        }
    }
}
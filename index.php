<?php
 
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/MediaHandler.php';
require_once 'includes/DownloadProcessor.php';

// Pass the required directory parameters to MediaHandler constructor
$mediaHandler = new MediaHandler(MEDIA_VIDEOS_DIR, MEDIA_MUSIC_DIR);

// Combine both videos and music
$videos = $mediaHandler->getVideos();
$music = $mediaHandler->getMusic();
$mediaFiles = array_merge($videos, $music);

// Count statistics
$videoCount = count($videos);
$musicCount = count($music);
$totalFiles = count($mediaFiles);

// Check dark mode preference
$darkMode = isset($_COOKIE['darkMode']) ? $_COOKIE['darkMode'] === 'true' : true;
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo $darkMode ? 'dark' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediaFlow | Premium Media Experience</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Video.js for better video controls -->
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <!-- Custom Styles -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        },
                        secondary: {
                            50: '#fdf4ff',
                            100: '#fae8ff',
                            200: '#f5d0fe',
                            300: '#f0abfc',
                            400: '#e879f9',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                            800: '#86198f',
                            900: '#701a75'
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 3s infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'float': 'float 3s ease-in-out infinite',
                        'blob': 'blob 7s infinite',
                        'blob-spin': 'spin 20s linear infinite',
                        'marquee': 'marquee 25s linear infinite',
                        'spin-slow': 'spin 6s linear infinite',
                    },
                    keyframes: {
                        glow: {
                            '0%': { boxShadow: '0 0 5px rgba(66, 153, 225, 0.5)' },
                            '100%': { boxShadow: '0 0 20px rgba(66, 153, 225, 0.8)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        blob: {
                            '0%': { transform: 'scale(1)' },
                            '33%': { transform: 'scale(1.1)' },
                            '66%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)' }
                        },
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-100%)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Light and Dark Mode Gradients */
        .bg-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
        }
        
        .dark .dark-bg-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .light-bg-gradient {
            background: linear-gradient(135deg, #e0f2fe 0%, #ddd6fe 50%, #fae8ff 100%);
        }
        
        /* Global Styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animations */
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
        }
        
        /* Video player enhancements */
        .video-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }
        
        .video-container:hover .video-controls {
            opacity: 1;
        }
        
        .video-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
            padding: 20px 10px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        /* 3D Card Effect */
        .card-3d {
            perspective: 1000px;
        }
        
        .card-3d-inner {
            transition: transform 0.5s;
            transform-style: preserve-3d;
        }
        
        .card-3d:hover .card-3d-inner {
            transform: rotateY(5deg) rotateX(5deg);
        }
        
        /* Glass Effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Animation classes */
        .animate-blob {
            animation: blob 7s infinite;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        
        /* Custom video.js theme */
        .vjs-matrix.video-js {
            background-color: transparent;
        }
        
        .vjs-matrix .vjs-big-play-button {
            background-color: rgba(138, 43, 226, 0.7);
            border: none;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 80px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        
        .vjs-matrix .vjs-big-play-button:hover {
            background-color: rgba(138, 43, 226, 0.9);
        }
        
        .vjs-matrix .vjs-control-bar {
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 100%);
        }
        
        /* Rotating cog animation */
        .animate-spin-slow {
            animation: spin 6s linear infinite;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-800 text-gray-800 dark:text-white min-h-screen custom-scrollbar">
    <!-- Hero Section -->
    <div class="light-bg-gradient dark:dark-bg-gradient shadow-lg">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <i class="fas fa-cloud-download-alt text-3xl text-indigo-600 dark:text-white animate-float"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    </div>
                    <h1 class="text-2xl font-bold text-indigo-600 dark:text-white">
                        <span class="inline-block">Media</span><span class="inline-block text-purple-600 dark:text-purple-400">Flow</span>
                    </h1>
                </div>
                <div class="hidden md:flex space-x-4">
                    <button id="nav-home" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm transition-all text-gray-700 dark:text-white">
                        <i class="fas fa-home mr-2"></i>Home
                    </button>
                    <button id="nav-videos" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm transition-all text-gray-700 dark:text-white">
                        <i class="fas fa-video mr-2"></i>Videos
                    </button>
                    <button id="nav-music" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm transition-all text-gray-700 dark:text-white">
                        <i class="fas fa-music mr-2"></i>Music
                    </button>
                </div>
                <div id="theme-toggle" class="rounded-full bg-white/20 p-2 backdrop-blur-sm cursor-pointer hover:bg-white/30 transition-all">
                    <i id="theme-icon" class="<?php echo $darkMode ? 'fas fa-sun' : 'fas fa-moon'; ?> text-gray-700 dark:text-white"></i>
                </div>
            </div>

            <div class="mt-12 mb-16 md:flex items-center justify-between">
                <div data-aos="fade-right" class="md:w-1/2 mb-8 md:mb-0">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-800 dark:text-white">Download your favorite <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">media</span> with ease</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-8 text-lg">Access and download your media collection from anywhere. Simply paste a URL to add new content to your library.</p>
                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-link text-gray-400"></i>
                            </div>
                            <input type="text" id="url-input" name="url" placeholder="Paste video or music URL here..." 
                                   class="w-full pl-10 pr-20 py-4 bg-white dark:bg-white/10 backdrop-blur-lg rounded-xl border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-700 dark:text-white placeholder-gray-400 outline-none shadow-sm" />
                            <div class="absolute right-3 top-3 flex items-center">
                                <span id="paste-button" class="cursor-pointer p-1 rounded-md text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Paste from clipboard">
                                    <i class="far fa-clipboard"></i>
                                </span>
                            </div>
                        </div>
                        <button id="downloadUrlBtn" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-4 px-8 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <i class="fas fa-cloud-download-alt mr-2"></i> Download
                        </button>
                    </div>
                    
                    <!-- Features badges -->
                    <div class="mt-8 flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-xs font-medium inline-flex items-center">
                            <i class="fas fa-bolt mr-1"></i> Fast Download
                        </span>
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 rounded-full text-xs font-medium inline-flex items-center">
                            <i class="fas fa-shield-alt mr-1"></i> Secure
                        </span>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-xs font-medium inline-flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Free
                        </span>
                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-medium inline-flex items-center">
                            <i class="fas fa-star mr-1"></i> Premium Quality
                        </span>
                    </div>
                </div>
                <div data-aos="fade-left" class="md:w-5/12">
                    <div class="relative card-3d">
                        <div class="card-3d-inner">
                            <!-- Background blobs for visual appeal -->
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500/30 dark:bg-blue-500/50 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-purple-500/30 dark:bg-purple-500/50 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                            <div class="absolute top-10 left-20 w-20 h-20 bg-pink-500/30 dark:bg-pink-500/50 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                            
                            <div class="relative z-10 bg-white dark:bg-slate-800/50 shadow-2xl rounded-2xl p-2 backdrop-blur-sm">
                                <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTJ8fG11c2ljJTIwc3R1ZGlvfGVufDB8fDB8fA%3D%3D&auto=format&fit=crop&w=800&q=60" 
                                     alt="Media Illustration" 
                                     class="rounded-xl shadow-lg w-full floating" />
                                     
                                <div class="absolute -bottom-4 -right-4 transform rotate-6">
                                    <div class="bg-white dark:bg-slate-700 shadow-lg rounded-lg p-2 w-16 h-16 flex items-center justify-center animate-spin-slow">
                                        <i class="fas fa-cog text-3xl text-blue-500 dark:text-blue-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="container mx-auto px-4 py-8">
        <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-3 gap-6 -mt-16">
            <div class="bg-white dark:bg-white/10 backdrop-blur-lg rounded-xl p-6 shadow-lg flex flex-col items-center card-hover">
                <div class="p-4 bg-blue-500/20 rounded-full mb-4 relative overflow-hidden">
                    <i class="fas fa-video text-blue-500 dark:text-blue-400 text-2xl relative z-10"></i>
                    <div class="absolute inset-0 bg-blue-500/10 rounded-full animate-ping opacity-75"></div>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white"><?php echo $videoCount; ?></h3>
                <p class="text-gray-600 dark:text-gray-300">Videos Available</p>
            </div>
            
            <div class="bg-white dark:bg-white/10 backdrop-blur-lg rounded-xl p-6 shadow-lg flex flex-col items-center card-hover">
                <div class="p-4 bg-purple-500/20 rounded-full mb-4 relative overflow-hidden">
                    <i class="fas fa-music text-purple-500 dark:text-purple-400 text-2xl relative z-10"></i>
                    <div class="absolute inset-0 bg-purple-500/10 rounded-full animate-ping opacity-75"></div>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white"><?php echo $musicCount; ?></h3>
                <p class="text-gray-600 dark:text-gray-300">Music Files</p>
            </div>
            
            <div class="bg-white dark:bg-white/10 backdrop-blur-lg rounded-xl p-6 shadow-lg flex flex-col items-center card-hover">
                <div class="p-4 bg-pink-500/20 rounded-full mb-4 relative overflow-hidden">
                    <i class="fas fa-cloud-download-alt text-pink-500 dark:text-pink-400 text-2xl relative z-10"></i>
                    <div class="absolute inset-0 bg-pink-500/10 rounded-full animate-ping opacity-75"></div>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-white"><?php echo $totalFiles; ?></h3>
                <p class="text-gray-600 dark:text-gray-300">Total Media</p>
            </div>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-center mb-8">
            <div class="inline-flex bg-gray-200 dark:bg-slate-700/50 p-1 rounded-lg">
                <button id="tab-all" class="tab-btn active px-4 py-2 rounded-lg transition-all font-medium text-gray-800 dark:text-white bg-white dark:bg-slate-600">All Media</button>
                <button id="tab-videos" class="tab-btn px-4 py-2 rounded-lg transition-all font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 hover:dark:text-white">Videos</button>
                <button id="tab-music" class="tab-btn px-4 py-2 rounded-lg transition-all font-medium text-gray-600 dark:text-gray-300 hover:text-gray-800 hover:dark:text-white">Music</button>
            </div>
        </div>
    </div>

    <!-- Media Library Section -->
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                <span id="media-section-title">Your Media Library</span>
                <span class="ml-2 px-2 py-1 text-xs bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-full"><?php echo $totalFiles; ?></span>
            </h2>
            <div class="flex space-x-2">
                <button id="view-grid" class="bg-white dark:bg-white/10 hover:bg-gray-100 dark:hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-white">
                    <i class="fas fa-th mr-2"></i>Grid
                </button>
                <button id="view-list" class="bg-white dark:bg-white/10 hover:bg-gray-100 dark:hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-white">
                    <i class="fas fa-list mr-2"></i>List
                </button>
            </div>
        </div>

        <?php if (!empty($mediaFiles)): ?>
            <div id="media-container" data-aos="fade-up" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($mediaFiles as $index => $media): ?>
                    <div class="media-card <?php echo in_array(pathinfo($media['name'], PATHINFO_EXTENSION), ['mp4', 'mov', 'avi', 'webm']) ? 'video-item' : (in_array(pathinfo($media['name'], PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg']) ? 'music-item' : 'other-item'); ?> bg-white dark:bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover group">
                        <div class="relative">
                            <?php 
                            $extension = pathinfo($media['name'], PATHINFO_EXTENSION);
                            $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm']);
                            $isAudio = in_array(strtolower($extension), ['mp3', 'wav', 'ogg']);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            ?>

                            <?php if ($isVideo): ?>
                                <div class="aspect-video bg-slate-800 flex items-center justify-center overflow-hidden">
                                    <!-- Using Video.js for better video controls -->
                                    <video 
                                        id="video-<?php echo $index; ?>" 
                                        class="video-js vjs-matrix" 
                                        controls 
                                        preload="auto" 
                                        data-setup='{
                                            "fluid": true,
                                            "playbackRates": [0.5, 1, 1.5, 2],
                                            "controlBar": {
                                                "fullscreenToggle": true,
                                                "pictureInPictureToggle": true
                                            }
                                        }'
                                        poster="<?php echo $isVideo ? 'api/thumbnail.php?video=' . urlencode($media['name']) : $media['thumbnail']; ?>"
                                    >
                                        <source src="<?php echo $media['path']; ?>" type="video/mp4">
                                        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video</p>
                                    </video>
                                </div>
                            <?php elseif ($isAudio): ?>
                                <div class="aspect-video bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="audio-waveform flex items-end justify-center space-x-1 h-16 w-24">
                                            <?php for($i = 0; $i < 9; $i++): ?>
                                                <div class="h-<?php echo rand(4, 16); ?> w-1 bg-white/70 rounded-full audio-bar"></div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <audio 
                                        id="audio-<?php echo $index; ?>" 
                                        class="hidden" 
                                        src="<?php echo $media['path']; ?>" 
                                        preload="metadata"
                                    ></audio>
                                    
                                    <button 
                                        onclick="toggleAudio('audio-<?php echo $index; ?>', this)" 
                                        class="play-audio-btn z-10 p-4 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-all">
                                        <i class="fas fa-play text-xl"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <img src="<?php echo $media['thumbnail']; ?>" alt="<?php echo $media['title']; ?>" class="w-full aspect-video object-cover" />
                            <?php endif; ?>
                            
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 text-xs rounded bg-white/20 backdrop-blur-sm text-gray-800 dark:text-white font-medium">
                                    <?php echo strtoupper($extension); ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-semibold mb-1 truncate text-gray-800 dark:text-white"><?php echo $media['title']; ?></h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4"><?php echo $media['description']; ?></p>
                            
                            <div class="flex justify-between items-center">
                                <a href="<?php echo $media['download_link']; ?>" download class="flex items-center space-x-1 text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium transition-colors">
                                    <i class="fas fa-download"></i>
                                    <span>Download</span>
                                </a>
                                <div class="flex space-x-2">
                                    <button class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 transition-colors text-gray-600 dark:text-gray-300">
                                        <i class="fas fa-share-alt text-xs"></i>
                                    </button>
                                    <div class="relative group">
                                        <button class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 transition-colors text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-ellipsis-v text-xs"></i>
                                        </button>
                                        <div class="absolute right-0 bottom-10 w-32 bg-white dark:bg-slate-800 rounded-lg shadow-lg py-2 hidden group-hover:block z-10">
                                            <a href="#" class="block px-4 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">View Details</a>
                                            <a href="#" class="block px-4 py-1 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700">Add to Playlist</a>
                                            <a href="#" class="block px-4 py-1 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-slate-700">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white dark:bg-white/10 backdrop-blur-sm rounded-xl p-12 text-center">
                <div class="mb-6">
                    <i class="fas fa-photo-film text-6xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-medium mb-2 text-gray-800 dark:text-white">No media files found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Your library is empty. Start by downloading some media!</p>
                <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Add Media
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recommended Section -->
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Recommended for You</h2>
        <div class="overflow-hidden relative">
            <div class="absolute top-0 bottom-0 left-0 w-20 bg-gradient-to-r from-gray-50 to-transparent dark:from-slate-900 dark:to-transparent z-10 pointer-events-none"></div>
            <div class="absolute top-0 bottom-0 right-0 w-20 bg-gradient-to-l from-gray-50 to-transparent dark:from-slate-900 dark:to-transparent z-10 pointer-events-none"></div>
            
            <div class="flex space-x-4 overflow-x-auto py-4 px-2 snap-x scrollbar-hide">
                <!-- Sample recommended items - in production these would be dynamically generated -->
                <?php for($i = 0; $i < 6; $i++): ?>
                    <div class="flex-none w-72 snap-center">
                        <div class="bg-white dark:bg-white/10 backdrop-blur-sm rounded-xl overflow-hidden shadow-lg card-hover group">
                            <div class="relative">
                                <div class="aspect-video bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                    <i class="fas <?php echo $i % 2 == 0 ? 'fa-video' : 'fa-music'; ?> text-4xl text-white/70"></i>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 text-xs rounded bg-white/20 backdrop-blur-sm text-white font-medium">
                                        <?php echo $i % 2 == 0 ? 'MP4' : 'MP3'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold mb-1 truncate text-gray-800 dark:text-white">Recommended Media <?php echo $i+1; ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Check out this trending content</p>
                                <div class="flex justify-end">
                                    <button class="px-3 py-1 bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 rounded-lg text-xs font-medium text-gray-700 dark:text-white transition">
                                        <i class="fas fa-plus mr-1"></i> Add to Library
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fixed bottom-8 right-8 p-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full shadow-lg hover:shadow-xl hover:scale-110 transition-all z-20">
        <i class="fas fa-plus text-white text-xl"></i>
    </button>

    <!-- Footer -->
    <footer class="mt-20 border-t border-gray-200 dark:border-white/10">
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-cloud-download-alt text-2xl text-indigo-600 dark:text-white"></i>
                        <h2 class="text-xl font-bold text-indigo-600 dark:text-white">
                            <span class="inline-block">Media</span><span class="inline-block text-purple-600 dark:text-purple-400">Flow</span>
                        </h2>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Download and manage your media with ease</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i class="fab fa-github text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 dark:border-white/10 pt-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                &copy; <?php echo date('Y'); ?> MediaFlow. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Toast for notifications -->
    <div id="toast" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-sm bg-white dark:bg-white/10 backdrop-blur-lg px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-y-full opacity-0 flex items-center z-50">
        <div class="p-2 rounded-full bg-green-500/20 mr-3">
            <i class="fas fa-check text-green-500"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-medium text-gray-800 dark:text-white" id="toast-title">Success!</h4>
            <p class="text-sm text-gray-600 dark:text-gray-300" id="toast-message">Your action was completed successfully.</p>
        </div>
        <button onclick="document.getElementById('toast').classList.add('translate-y-full', 'opacity-0')" class="ml-4 text-gray-400 hover:text-gray-600 dark:hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Video.js for better video playback -->
    <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
    <!-- AOS library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animation library
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true
        });

        // Dark mode toggle
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            const isDarkMode = html.classList.toggle('dark');
            const themeIcon = document.getElementById('theme-icon');
            
            if (isDarkMode) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                document.cookie = "darkMode=true; path=/; max-age=31536000"; // 1 year
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                document.cookie = "darkMode=false; path=/; max-age=31536000";
            }
        });

        // Initialize Video.js players
        document.querySelectorAll('.video-js').forEach(function(videoElement) {
            let player = videojs(videoElement.id, {
                controls: true,
                autoplay: false,
                preload: 'auto',
                fluid: true,
                responsive: true,
                playbackRates: [0.5, 1, 1.5, 2]
            });

            // Add custom fullscreen button if needed
            player.on('play', function() {
                const videoCards = document.querySelectorAll('.video-item');
                videoCards.forEach(card => {
                    if (!card.contains(videoElement)) {
                        const otherPlayer = videojs(card.querySelector('video').id);
                        if (!otherPlayer.paused()) {
                            otherPlayer.pause();
                        }
                    }
                });
            });
        });

        // Audio toggle functionality 
        function toggleAudio(audioId, button) {
            const audio = document.getElementById(audioId);
            const waveformBars = button.closest('.aspect-video').querySelectorAll('.audio-bar');
            
            if (audio.paused) {
                // Stop all other audio first
                document.querySelectorAll('audio').forEach(a => {
                    if (a.id !== audioId && !a.paused) {
                        a.pause();
                        const otherButton = a.parentElement.querySelector('.play-audio-btn i');
                        if (otherButton) {
                            otherButton.className = 'fas fa-play text-xl';
                        }
                        
                        // Reset other waveform animations
                        const otherWaveform = a.closest('.aspect-video').querySelectorAll('.audio-bar');
                        if (otherWaveform) {
                            otherWaveform.forEach(bar => {
                                bar.style.animation = '';
                            });
                        }
                    }
                });
                
                // Play this audio
                audio.play();
                button.querySelector('i').className = 'fas fa-pause text-xl';
                
                // Animate waveform
                waveformBars.forEach((bar, index) => {
                    bar.style.animation = `equalizer 1s infinite ${index * 0.2}s`;
                });
            } else {
                // Pause this audio
                audio.pause();
                button.querySelector('i').className = 'fas fa-play text-xl';
                
                // Stop waveform animation
                waveformBars.forEach(bar => {
                    bar.style.animation = '';
                });
            }
        }

        // Category filtering
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active', 'bg-white', 'dark:bg-slate-600'));
                
                // Add active class to clicked tab
                this.classList.add('active', 'bg-white', 'dark:bg-slate-600');
                
                const sectionTitle = document.getElementById('media-section-title');
                
                // Filter content based on tab
                if (this.id === 'tab-all') {
                    document.querySelectorAll('.media-card').forEach(card => card.style.display = '');
                    sectionTitle.textContent = 'Your Media Library';
                } else if (this.id === 'tab-videos') {
                    document.querySelectorAll('.media-card').forEach(card => {
                        if (card.classList.contains('video-item')) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    sectionTitle.textContent = 'Your Video Library';
                } else if (this.id === 'tab-music') {
                    document.querySelectorAll('.media-card').forEach(card => {
                        if (card.classList.contains('music-item')) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    sectionTitle.textContent = 'Your Music Library';
                }
            });
        });

        // URL form submission with better feedback
        document.getElementById('downloadUrlBtn').addEventListener('click', function() {
            const url = document.getElementById('url-input').value.trim();
            if (url) {
                // Show loading state
                this.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Processing...';
                this.disabled = true;
                
                // Make AJAX request to process URL
                fetch('api/process-url.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ url: url })
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    this.innerHTML = '<i class="fas fa-cloud-download-alt mr-2"></i> Download';
                    this.disabled = false;
                    
                    // Show toast notification
                    const toast = document.getElementById('toast');
                    const toastTitle = document.getElementById('toast-title');
                    const toastMessage = document.getElementById('toast-message');
                    
                    if (data.success) {
                        toastTitle.textContent = 'Success!';
                        toastMessage.textContent = data.message || 'File downloaded successfully.';
                        toast.classList.remove('translate-y-full', 'opacity-0');
                        
                        // Clear input
                        document.getElementById('url-input').value = '';
                        
                        // Hide toast after 5 seconds
                        setTimeout(() => {
                            toast.classList.add('translate-y-full', 'opacity-0');
                        }, 5000);
                        
                        // Refresh media list after successful download
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        toastTitle.textContent = 'Error!';
                        toastMessage.textContent = data.message || 'Failed to process URL.';
                        toast.classList.remove('translate-y-full', 'opacity-0');
                        
                        // Hide toast after 5 seconds
                        setTimeout(() => {
                            toast.classList.add('translate-y-full', 'opacity-0');
                        }, 5000);
                    }
                })
                .catch(error => {
                    // Reset button state
                    this.innerHTML = '<i class="fas fa-cloud-download-alt mr-2"></i> Download';
                    this.disabled = false;
                    
                    // Show error toast
                    const toast = document.getElementById('toast');
                    const toastTitle = document.getElementById('toast-title');
                    const toastMessage = document.getElementById('toast-message');
                    
                    toastTitle.textContent = 'Error!';
                    toastMessage.textContent = 'Network error occurred. Please try again.';
                    toast.classList.remove('translate-y-full', 'opacity-0');
                    
                    // Hide toast after 5 seconds
                    setTimeout(() => {
                        toast.classList.add('translate-y-full', 'opacity-0');
                    }, 5000);
                });
            }
        });

        // Paste from clipboard functionality
        document.getElementById('paste-button').addEventListener('click', function() {
            navigator.clipboard.readText()
                .then(text => {
                    document.getElementById('url-input').value = text;
                })
                .catch(err => {
                    console.log('Clipboard access denied', err);
                });
        });

        // Grid/List view toggle
        document.getElementById('view-grid').addEventListener('click', function() {
            const container = document.getElementById('media-container');
            container.classList.remove('grid-cols-1');
            container.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
        });

        document.getElementById('view-list').addEventListener('click', function() {
            const container = document.getElementById('media-container');
            container.classList.remove('sm:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
            container.classList.add('grid-cols-1');
        });

        // Add equalizer animation for audio
        const style = document.createElement('style');
        style.textContent = `
            @keyframes equalizer {
                0% { height: 0.5rem; }
                50% { height: 2rem; }
                100% { height: 0.5rem; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
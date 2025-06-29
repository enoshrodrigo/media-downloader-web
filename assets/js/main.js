// filepath: /media-downloader/media-downloader/assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    const downloadForm = document.getElementById('download-form');
    const mediaList = document.getElementById('media-list');
    const urlInput = document.getElementById('url-input');

    // Fetch media files on page load
    fetchMediaFiles();

    // Handle form submission for URL input
    downloadForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const url = urlInput.value.trim();
        if (url) {
            processUrl(url);
        }
    });

    function fetchMediaFiles() {
        fetch('api/fetch-media.php')
            .then(response => response.json())
            .then(data => {
                displayMediaFiles(data);
            })
            .catch(error => console.error('Error fetching media files:', error));
    }

    function displayMediaFiles(mediaFiles) {
        mediaList.innerHTML = '';
        mediaFiles.forEach(file => {
            const mediaCard = document.createElement('div');
            mediaCard.className = 'media-card';
            mediaCard.innerHTML = `
                <h3>${file.name}</h3>
                <p>${file.type}</p>
                <a href="${file.downloadUrl}" class="btn btn-primary">Download</a>
            `;
            mediaList.appendChild(mediaCard);
        });
    }

    function processUrl(url) {
        fetch('api/process-url.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ url: url })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Download started for the provided URL.');
            } else {
                alert('Error processing the URL: ' + data.message);
            }
        })
        .catch(error => console.error('Error processing URL:', error));
    }
});
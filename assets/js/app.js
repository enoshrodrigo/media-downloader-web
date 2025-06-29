// filepath: /media-downloader/media-downloader/assets/js/app.js

document.addEventListener('DOMContentLoaded', function() {
    const downloadButtons = document.querySelectorAll('.download-btn');
    const urlForm = document.getElementById('url-form');
    const urlInput = document.getElementById('url-input');
    const messageBox = document.getElementById('message-box');

    downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const mediaId = this.dataset.mediaId;
            downloadMedia(mediaId);
        });
    });

    urlForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const url = urlInput.value.trim();
        if (url) {
            processUrl(url);
        } else {
            showMessage('Please enter a valid URL.', 'error');
        }
    });

    function downloadMedia(mediaId) {
        fetch(`api/download.php?id=${mediaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Download started!', 'success');
                } else {
                    showMessage('Download failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                showMessage('An error occurred. Please try again.', 'error');
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
                showMessage('URL processed successfully!', 'success');
            } else {
                showMessage('Failed to process URL. Please check the link.', 'error');
            }
        })
        .catch(error => {
            showMessage('An error occurred while processing the URL.', 'error');
        });
    }

    function showMessage(message, type) {
        messageBox.textContent = message;
        messageBox.className = type === 'error' ? 'alert alert-danger' : 'alert alert-success';
        messageBox.style.display = 'block';
        setTimeout(() => {
            messageBox.style.display = 'none';
        }, 3000);
    }
});
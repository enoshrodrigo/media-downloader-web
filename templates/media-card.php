<div class="media-card">
    <div class="media-thumbnail">
        <img src="<?php echo $media['thumbnail']; ?>" alt="<?php echo $media['title']; ?>" class="img-fluid">
    </div>
    <div class="media-details">
        <h5 class="media-title"><?php echo $media['title']; ?></h5>
        <p class="media-description"><?php echo $media['description']; ?></p>
        <div class="media-actions">
            <a href="<?php echo $media['download_link']; ?>" class="btn btn-primary" download>Download</a>
        </div>
    </div>
</div>
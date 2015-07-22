<?php
$ctaCopy = get_field("data_capture_notifications_{$detail}_cta_copy", 'option'); ?>

<div class="member-alert">
    <div class="container">
        <p><?php the_field("data_capture_notifications_{$detail}_copy", 'option'); ?>
            <?php
            if ($ctaCopy) { ?><a href="<?php echo cpfc_home_url(CPFC_MEMBERS_DATA_CAPTURE_URL) . '?details=' . $detail; ?>" id="data-capture-panel" class="btn"><?php echo htmlentities($ctaCopy); ?></a>
            <?php
            } ?></p>
    </div>
</div>
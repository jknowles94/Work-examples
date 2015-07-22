<section class="full-width bg-grey footer-logos">
    <div class="container">
        <?php
        $partners = get_field('footer_partners', 'option');
        if ($partners) { ?>
        <ul>
            <?php
            foreach ($partners as $partner) { ?>
            <li><a href="<?php echo isset($partner['url']) ? $partner['url'] : ''; ?>" target="_blank"><img src="<?php echo isset($partner['image']['url']) ? $partner['image']['url'] : ''; ?>" class="img-responsive" alt="<?php echo isset($partner['text']) ? $partner['text'] : ''; ?>"></a></li>
            <?php
            } ?>
        </ul>
        <?php
        } ?>
    </div>
</section>
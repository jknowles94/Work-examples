    <ul id="og-grid" class="og-grid event-grid">
    <?php if (have_posts()): while (have_posts()) : the_post(); ?>

    
        <li class="grid-item">
            
            <a href="<?php the_permalink(); ?>" data-largesrc="<?php the_field('event_image'); ?>" data-title="<?php the_title(); ?>" data-description="<?php the_content(); ?>">
                <?php if (has_post_thumbnail( $post->ID ) ): ?>
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                <div class="img-background"><img src="<?php echo $image[0]; ?>"/></div>
                <?php endif; ?>  
                <div class="hover-state">
                    <h3><?php the_title(); ?></h3>
                </div>
            </a>
        </li>
     
    <?php endwhile; ?>
    <?php endif; ?>

    </ul>

    <style>
    .menu-item-35 > a {
        background: #d51e49;
        color: #fff!important;
    }

    .menu-item-35 i:before {
        transform: rotate(90deg);
    }

    .menu-item-35 .sub-menu {
        display: block!important;
    }
    </style>
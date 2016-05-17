<?php  /* Template Name: Events */  get_header(); ?>

		<section class="header-img">
            <?php 
            $headerImg = get_field('header_image');
            if( !empty($headerImg) ): ?>
                <img src="<?php echo $headerImg; ?>" class="img-responsive"/>
            <?php else: ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/header-img.png" class="img-responsive"/>
            <?php endif; ?>
        </section>

        <section class="content-container">

            <div class="side-nav">
                <?php html5blank_nav(); ?>
            </div>
            <div class="main-content">
                <div class="content-title">
                    <h1><?php the_title();?></h1>

                    <a href="#" class="nav-mobile-toggle sb-toggle-left">
                        <i class="icon-navicon"></i>
                    </a>
                </div>
                <div class="content-body">
                    

                    <?php if (have_posts()): while (have_posts()) : the_post(); ?>
                        
                        <div class="body-container">
                            <?php the_content(); ?>
                            <p><strong>FILTER BY CATEGORY</strong></p>
                            <?php echo do_shortcode( '[ULWPQSF id=1210]' ); ?>
                        </div>
                        

                    <?php endwhile; ?>

                    <?php endif; ?>

                    
                    
                    <?php $events = new WP_Query( array( 'post_type' => 'event', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page'=>-1, 'tax_query' => array(
        array(
            'taxonomy' => 'main-event',
            'field'    => 'slug',
            'terms'    => 'main-event',
        ),
    ) ) );?>
                    <ul id="og-grid" class="og-grid event-grid">
                    <?php while ( $events->have_posts() ) : $events->the_post(); ?>

                    
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
                    </ul>
                    


                </div>
            </div>
        </section>

<?php get_footer(); ?>
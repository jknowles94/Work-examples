<?php  /* Template Name: Success Stories */  get_header(); ?>

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
                    <?php $story = new WP_Query( array( 'post_type' => 'success-stories', 'orderby' => 'date', 'order' => 'DESC' ) );?>
                    <div class="news-grid">
                    <?php while ( $story->have_posts() ) : $story->the_post(); ?>
                            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                            <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                            <div class="grid-item" style="background-image: url('<?php echo $image[0]; ?>');">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                    <div class="nav-fix">
                                        <h3><?php the_title(); ?></h3>
                                    </div>
                                </a>
                            </div>  
                            <?php endif; ?>          
                        
                    <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>

<?php get_footer(); ?>

<?php  /* Template Name: Navigation */  get_header(); ?>

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
                        <?php
                        $cc = get_the_content();
                        if($cc != '') { ?>
                        <div class="body-container">
                            <?php the_content(); ?>
                        </div>
                        <?php } ?>
                        

                    <?php endwhile; ?>

                    <?php endif; ?>

                    <div class="news-grid">
                        <?php
                            $mypages = get_pages( array( 'child_of' => $post->ID, 'sort_order' => 'desc' ));

                            foreach( $mypages as $page ) {      
                                $content = $page->post_content;

                            ?>
                            <div class="grid-item" style="background-image: url('<?php echo get_the_post_thumbnail_url($page->ID); ?>');">
                                <a href="<?php echo get_page_link( $page->ID ); ?>">
                                    <div class="nav-fix">
                                        <h3><?php echo $page->post_title; ?></h3>
                                    </div>
                                </a>
                            </div>
                            <?php
                            }   
                        ?>
                            
                    </div>
                    


                </div>
            </div>
        </section>

<?php get_footer(); ?>
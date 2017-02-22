<?php  /* Template Name: Fundraise */  get_header(); ?>

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
                        <?php if( have_rows('fundraise_nav') ): ?>
                        <?php while( have_rows('fundraise_nav') ): the_row(); 

                        $image = get_sub_field('background_image');
                        $title = get_sub_field('title');
                        $link = get_sub_field('link');

                        ?>
                        <div class="grid-item" style="background-image: url('<?php echo $image; ?>');">
                            <?php if( $link ): ?>
                                <a href="<?php echo $link; ?>">
                            <?php endif; ?>
                                <div class="nav-fix">
                                    <h3><?php echo $title; ?></h3>
                                </div>
                            <?php if( $link ): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                        <?php endif; ?>
                        
                        

                        <?php if( have_rows('small_carousel') ): ?>
                        <div class="sm-gallery">
                            <div class="cycle-slideshow" data-cycle-fx="fade" data-cycle-swipe="true" data-cycle-timeout="0" data-cycle-slides="> .item">
                                <?php while( have_rows('small_carousel') ): the_row(); 

                                $image = get_sub_field('image');
                                $caption = get_sub_field('caption');

                                ?>
                                <div class="item">
                                    <div class="header-text">
                                        <?php echo $caption; ?>
                                    </div>
                                    <img src="<?php echo $image; ?>" alt="" />
                                </div>

                                <?php endwhile; ?>

                                <a href="#" class="cycle-prev"><i class="icon-chevron-left"></i></a>
                                <a href="#" class="cycle-next"><i class="icon-chevron-right"></i></a>

                            </div>
                        </div>
                        <?php endif; ?>
                            
                    </div>
                    


                </div>
            </div>
        </section>

<?php get_footer(); ?>
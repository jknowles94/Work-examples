<?php  /* Template Name: Section Navigation */  get_header(); ?>

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
                    <?php if( have_rows('section_nav') ): ?>
                    <div class="nav-grid">

                        <?php while( have_rows('section_nav') ): the_row(); 

                        $image = get_sub_field('background_image');
                        $title = get_sub_field('title');
                        $link = get_sub_field('link');

                        ?>
                        <div class="grid-item" style="background-image: url('<?php echo $image; ?>');">
                            <?php if( $link ): ?>
                                <a href="<?php echo $link; ?>">
                            <?php endif; ?>
                                <div>
                                    <h1><?php echo $title; ?></h1>
                                </div>
                            <?php if( $link ): ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

<?php get_footer(); ?>

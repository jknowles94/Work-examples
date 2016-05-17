<?php get_header(); ?>

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
                <h1>Challenges & events</h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">

                <?php get_template_part('search_loop'); ?>
                
            </div>
        </div>
    </section>

<?php get_footer(); ?>

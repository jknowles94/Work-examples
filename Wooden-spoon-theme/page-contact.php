<?php  /* Template Name: Contact */  get_header(); ?>

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
                    <div class="body-container">
                        <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                            <?php the_content(); ?>
                            
                            
                        <?php endwhile; ?>

                        <?php endif; ?>

                        <?php $url = $_SERVER["REQUEST_URI"];
                            if (strpos($url, "region")!==false){ ?>
                                <p>This is a Regional Contact Form where your enquiry will be sent directly to the regional committee for Wooden Spoon.</p>
                                <p>To contact the Wooden Spoon head office <a href="/contact/">click here</a></p>
                            <?php } else { ?>
                                <p>To contact your local Wooden Spoon representative visit <a href="/near-you/">Near You</a> to find your local Wooden Spoon regional committee.</p>
                            <?php } ?>

                        <?php
                        echo do_shortcode('[contact-form-7 id="195" title="Contact"]');
                        ?>

                    </div>
                </div>
            </div>
        </section>

<?php get_footer(); ?>

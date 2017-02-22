<?php  /* Template Name: Profile */  get_header(); ?>

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
                    
                    <?php if( have_rows('profile') ): ?>
                    <ul id="og-grid" class="og-grid profile-grid">
                        <?php while( have_rows('profile') ): the_row(); 

                        $image = get_sub_field('profile_image');
                        $title = get_sub_field('profile_name');
                        $description = get_sub_field('profile_description');

                        ?>
                            <li class="grid-item">
                                <a data-title="<?php echo $title; ?>" data-description="<?php echo $description; ?>">
                                    <div class="img-background"><img src="<?php echo $image; ?>"/></div>
                                    <div class="profile-name">
                                        <p><?php echo $title; ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                    <?php endif; ?>
                    


                </div>
            </div>
        </section>

<?php get_footer(); ?>
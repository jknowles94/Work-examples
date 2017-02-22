<?php  /* Template Name: Marauder */  get_header(); ?>

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
                        </div>

                    <?php endwhile; ?>

                    <?php endif; ?>

                    <div class="marauder-tab">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#fixtures" aria-controls="fixtures" role="tab" data-toggle="tab"><h1>Fixtures</h1></a></li>
                            <li role="presentation"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab"><h1>Contact Info</h1></a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="fixtures">
                                <div class="body-container">
                                    <?php the_field('fixtures'); ?>   
                                </div>                            
                            </div>
                            <div role="tabpanel" class="tab-pane" id="contact">
                                <div class="body-container">
                                    <?php the_field('contact_info'); ?>   
                                </div> 
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>

<?php get_footer(); ?>

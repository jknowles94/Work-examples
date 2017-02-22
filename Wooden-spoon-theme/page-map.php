<?php  /* Template Name: Map */  get_header(); ?>

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

                        <div class="row">
                            <div class="col-md-4">
                                <div id="sidebar">
                                    <div id="filters">
                                      <input id="filter-input" placeholder="Search by postcode...">
                                      <button id="filter-button" class="btn">Search</button>
                                    </div>
                                    <div id="regions"></div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="map-container">
                                    <div id="uk-map" style="height:800px;"></div>
                                    <div id="loader-screen">
                                      <div class="loader">Loading...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php get_footer(); ?>


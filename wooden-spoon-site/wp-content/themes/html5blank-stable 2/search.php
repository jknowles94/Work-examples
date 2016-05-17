

<?php get_header(); ?>

	<section class="header-img">
        <?php $url = $_SERVER["REQUEST_URI"];
                            if (strpos($url, "event-cat")!==false){ ?>
            <img src="<?php echo home_url(); ?>/wp-content/uploads/2016/03/0058_Challenges-Events-header-image.png" class="img-responsive"/>
            <?php } else { ?>
            <img src="<?php echo home_url(); ?>/wp-content/uploads/2016/03/0025_Main-news-header.png" class="img-responsive"/>
            <?php } ?>
    </section>

    <section class="content-container">

        <div class="side-nav">
            <?php html5blank_nav(); ?>
        </div>
        <div class="main-content">
            <div class="content-title">
                <?php $url = $_SERVER["REQUEST_URI"];
                            if (strpos($url, "event-cat")!==false){ ?>
                            <h1>CHALLENGES & EVENTS</h1>
                        <?php } else { ?>
                        <h1>NEWS</h1>
                        <?php } ?>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">
                <?php $url = $_SERVER["REQUEST_URI"];
                    if (strpos($url, "event-cat")!==false){ ?>
                    <?php $eventTxt = new WP_Query( array( 'pagename' => 'get-involved/challenges-events/' ) ); ?>
                	<div class="body-container">
                        <?php while ( $eventTxt->have_posts() ) : $eventTxt->the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; ?>
    					<p><strong>FILTER BY CATEGORY</strong></p>
    					<?php echo do_shortcode( '[ULWPQSF id=1210]' ); ?>
    				</div>
                    <?php get_template_part('search_loop_event'); ?>
                     <?php } else { ?>
                    <div class="body-container">
                        <p><strong>FILTER BY CATEGORY</strong></p>
                        <?php echo do_shortcode( '[ULWPQSF id=1211]' ); ?>
                    </div>
                    <?php get_template_part('search_loop'); ?>
                     <?php } ?>

            </div>
        </div>
    </section>

<?php get_footer(); ?>

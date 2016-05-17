<?php  /* Template Name: Home Page */  get_header(); ?>



        <?php if( have_rows('homepage_carousel') ): ?>
		<section class="fullwidth-bg header-img header-img-lg">
            <div class="cycle-slideshow" data-cycle-fx="fade" data-cycle-swipe="true" data-cycle-timeout="0" data-cycle-slides="> .item">

                <?php while( have_rows('homepage_carousel') ): the_row(); 

                    $image = get_sub_field('slide_image');
                    $title = get_sub_field('slide_title');
                    $text = get_sub_field('slide_text');
                    $link = get_sub_field('slide_link');

                    ?>

                    <div class="item">
                        <a href="<?php echo $link; ?>">
                            <div class="header-text">
                                <div class="container">

                                    <h1><?php echo $title; ?></h1>
                                    <?php if( $text ): ?>
                                        <div class="linebreak"></div>
                                        <p><?php echo $text; ?></p>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <img src="<?php echo $image; ?>" alt="" />
                        </a>
                    </div>
                <?php endwhile; ?>

                <a href="#" class="cycle-prev"><i class="icon-chevron-left"></i></a>
                <a href="#" class="cycle-next"><i class="icon-chevron-right"></i></a>

            </div>
        </section>
        <?php endif; ?>


        
        <section class="fullwidth home-nav">
            <div class="container">
                <?php if( have_rows('section_navigation') ): ?>
                <div class="nav-grid">
                    <?php while( have_rows('section_navigation') ): the_row(); 

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
                
                <div class="home-social">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#social" aria-controls="social" role="tab" data-toggle="tab"><h1>Social Feed</h1></a></li>
                        <li role="presentation"><a href="#news" aria-controls="news" role="tab" data-toggle="tab"><h1>Latest News</h1></a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="social">
                            <script src="//assets.juicer.io/embed.js" type="text/javascript"></script>
                            <link href="//assets.juicer.io/embed.css" media="all" rel="stylesheet" type="text/css" />
                            <ul class="juicer-feed" data-feed-id="wooden-spoon-new" data-per="6"></ul>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="news">
                            <?php $news = new WP_Query( array( 'post_type' => 'post', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page' => 6, 'tax_query' => array(
        array(
            'taxonomy' => 'news',
            'field'    => 'slug',
            'terms'    => 'news',
        ),
    ) ) );?>

                            
                                <div class="news-grid">
                                    <?php while ( $news->have_posts() ) : $news->the_post(); ?>
                                    <?php if (has_post_thumbnail( $post->ID ) ): ?>
                                    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                                    <div class="grid-item" style="background-image: url('<?php echo $image[0]; ?>');">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <div>
                                                <p class="date"><?php the_date('d.m.Y'); ?></p>
                                                <h3><?php the_title(); ?></h3>
                                            </div>
                                        </a>
                                    </div>  
                                    <?php endif; ?> 
                                    <?php endwhile; ?>         
                                </div>
                            
                            <a class="btn show-more" href="/news/">Show More</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

<?php get_footer(); ?>

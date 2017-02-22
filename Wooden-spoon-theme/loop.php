


	
	    <div class="news-grid">
	    	<?php $news = new WP_Query( array( 'post_type' => 'post', 'orderby' => 'date', 'order' => 'DESC', 'posts_per_page'=>-1, 'tax_query' => array(
		array(
			'taxonomy' => 'news',
			'field'    => 'slug',
			'terms'    => 'news',
		),
	) ) );?>
	    	<?php while ( $news->have_posts() ) : $news->the_post(); ?>

	    	<?php if (has_post_thumbnail( $post->ID ) ): ?>
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
	        <div class="grid-item" style="background-image: url('<?php echo $image[0]; ?>');">
	            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
	                <div>
	                    <p class="date"><?php the_time('d.m.y'); ?></p>
	                    <h3><?php the_title(); ?></h3>
	                </div>
	            </a>
	        </div>  
	        <?php endif; ?>   
	        <?php endwhile; ?>
		</div>

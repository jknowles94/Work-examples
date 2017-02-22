	<div class="news-grid">	
		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
		
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
	        <div class="grid-item" style="background-image: url('<?php echo $image[0]; ?>');">
	            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
	                <div>
	                    <p class="date"><?php the_date('d.m.Y'); ?></p>
	                    <h3><?php the_title(); ?></h3>
	                </div>
	            </a>
	        </div> 
	    

		<?php endwhile; ?>

		<?php endif; ?>
	</div>

	<style>
	.menu-item-33 > a {
		background: #d51e49;
		color: #fff!important;
	}

	.menu-item-33 i:before {
		transform: rotate(90deg);
	}

	.menu-item-33 .sub-menu {
		display: block!important;
	}
	</style>
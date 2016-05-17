
	    	<div class="content-title">
                <h1><?php 
                	_e( 'News', 'html5blank' );
                 ?></h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">

		    	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<div class="body-container">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); // Dynamic Content ?>
						<div class="pagination">

							<?php $nextPost = get_next_post(true);?>
								<div class="previous" style="background-image:url('<?php echo get_the_post_thumbnail_url($nextPost->ID); ?>');">
									<?php next_post_link('%link', '<span>Previous Story</span><p>%title</p>'); ?>
								</div>
							

							<div class="home"><h3><a href="/news/">Back to news home</a></h3></div> 
							
							<?php $prevPost = get_previous_post(true);?>
							<div class="next" style="background-image:url('<?php echo get_the_post_thumbnail_url($prevPost->ID); ?>');"><?php previous_post_link('%link', '<span>Next Story</span><p>%title</p>'); ?>
							</div>
							
						</div>
					</div>

		    	<?php endwhile; ?>

				<?php endif; ?>

			</div>


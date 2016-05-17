	    	<div class="content-title">
                <h1><?php the_title(); ?></h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<div class="body-container">
						<div class="contact-btn">
							<a href="<?php echo home_url(); ?>/contact/?regionID=<?php echo $post->ID; ?>">
								Contact
							</a>
						</div>
						<?php the_content(); // Dynamic Content ?>
					</div>
		    	
					<div class="region-tab">
	                    <!-- Nav tabs -->
	                    <ul class="nav nav-tabs" role="tablist">
	                        <li role="presentation" class="active"><a href="#news" aria-controls="news" role="tab" data-toggle="tab"><h1>News</h1></a></li>
	                        <li role="presentation"><a href="#events" aria-controls="events" role="tab" data-toggle="tab"><h1>Events</h1></a></li>
	                        <li role="presentation"><a href="#projects" aria-controls="projects" role="tab" data-toggle="tab"><h1>Projects</h1></a></li>
	                    </ul>

	                    <!-- Tab panes -->
	                    <div class="tab-content">
	                        <div role="tabpanel" class="tab-pane active" id="news">
	                        	
								
	                            <div class="news-grid">
	                            	<?php
	                            	global $post;

	                            	$term = get_the_terms($post->id, 'regions');
	                            		                        	 
		                        	$loop = new WP_Query( array( 'post_type' => 'post','orderby' => 'date', 'posts_per_page'=>-1, 'order' => 'DESC','tax_query' => array(
											array(
												'taxonomy' => 'regions',
												'field' => 'slug',
												'terms' => $term[0]
											)
										) ) ); ?>

									<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

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
							        <?php wp_reset_query(); ?>
	                            </div>
	                        </div>
	                        <div role="tabpanel" class="tab-pane" id="events">
	                            <?php 
	                            global $post;

	                            $term = get_the_terms($post->id, 'regions');

	                            $events = new WP_Query( array( 'post_type' => 'event', 'orderby' => 'date', 'posts_per_page'=>-1, 'order' => 'DESC', 'tax_query' => array(
											array(
												'taxonomy' => 'regions',
												'field' => 'slug',
												'terms' => $term[0]
											)
										) ) );?>

								<ul id="og-grid" class="og-grid event-grid">

			                    <?php while ( $events->have_posts() ) : $events->the_post(); ?>

			                    
			                        <li class="grid-item">
			                            
			                            <a href="<?php the_permalink(); ?>" data-largesrc="<?php the_field('event_image'); ?>" data-title="<?php the_title(); ?>" data-description="<?php the_content(); ?>">
			                                <?php if (has_post_thumbnail( $post->ID ) ): ?>
			                                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
			                                <div class="img-background"><img src="<?php echo $image[0]; ?>"/></div>
			                                <?php endif; ?>  
			                                <div class="hover-state">
			                                    <h3><?php the_title(); ?></h3>
			                                </div>
			                            </a>
			                        </li>
			                     
			                    
			                    <?php endwhile; ?>
			                    </ul>
			                    <?php wp_reset_query(); ?>
	                        </div>
	                        <div role="tabpanel" class="tab-pane" id="projects">
	                            <div class="project">
	                            	<?php if( have_rows('project') ): ?>
		                                <div class="cycle-slideshow" data-cycle-fx="fade" data-cycle-swipe="true" data-cycle-timeout="0" data-cycle-slides="> .item">
		                                	
		                                	<?php while( have_rows('project') ): the_row(); 

					                        $projectImage = get_sub_field('project_image');
					                        $projectText = get_sub_field('project_text');

					                        ?>
		                                    <div class="item">
		                                        <div class="header-text">
		                                            <?php echo $projectText; ?>
		                                        </div>

		                                        <img src="<?php echo $projectImage; ?>" alt="" />
		                                    </div>
		                                    <?php endwhile; ?>
		                                    

		                                    <a href="#" class="cycle-prev"><i class="icon-chevron-left"></i></a>
		                                    <a href="#" class="cycle-next"><i class="icon-chevron-right"></i></a>

		                                </div>
	                                <?php endif; ?>
	                            </div>
	                        </div>
	                    </div>

	                </div>
	            <?php endwhile; ?>

				<?php endif; ?>
			</div>
			<style>
			.menu-item-36 > a {
				background: #d51e49;
    			color: #fff!important;
			}

			.menu-item-36 i:before {
				transform: rotate(90deg);
			}

			.menu-item-36 .sub-menu {
				display: block!important;
			}
			</style>


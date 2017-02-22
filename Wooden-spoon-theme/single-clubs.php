	    	<div class="content-title">
                <h1><?php the_title(); ?></h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<div class="body-container">
						<?php the_content(); // Dynamic Content ?>
					</div>
		    	
					<div class="club-tab">
	                    <!-- Nav tabs -->
	                    <ul class="nav nav-tabs" role="tablist">
	                        <li role="presentation" class="active"><a href="#contact" aria-controls="contact" role="tab" data-toggle="tab"><h1>Contact</h1></a></li>
	                        <li role="presentation"><a href="#projects" aria-controls="projects" role="tab" data-toggle="tab"><h1>Projects</h1></a></li>
	                    </ul>

	                    <!-- Tab panes -->
	                    <div class="tab-content">
	                        <div role="tabpanel" class="tab-pane active" id="contact">
	                        	<div class="body-container">
	                        		<?php the_field('club_info'); ?>
	                        	</div>
	                        </div>
	                        <div role="tabpanel" class="tab-pane" id="projects">
	                            <div class="project">
	                            	<?php if( have_rows('gallery') ): ?>
		                                <div class="cycle-slideshow" data-cycle-fx="fade" data-cycle-swipe="true" data-cycle-timeout="0" data-cycle-slides="> .item">
		                                	
		                                	<?php while( have_rows('gallery') ): the_row(); 

					                        $projectImage = get_sub_field('gallery_image');
					                        $projectText = get_sub_field('gallery_text');

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


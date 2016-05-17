
	    	<div class="content-title">
                <h1><?php if (is_singular('success-stories')) {
                	_e( 'Success Stories', 'html5blank' );
                } ?></h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">

		    	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<div class="body-container">
						<h1><?php the_title(); ?></h1>
						<?php the_content(); // Dynamic Content ?>
					</div>
		    	<?php endwhile; ?>

				<?php endif; ?>
			</div>

			<style>
			.menu-item-18 > a {
				background: #d51e49;
    			color: #fff!important;
			}

			.menu-item-18 i:before {
				transform: rotate(90deg);
			}

			.menu-item-18 .sub-menu {
				display: block!important;
			}
			</style>
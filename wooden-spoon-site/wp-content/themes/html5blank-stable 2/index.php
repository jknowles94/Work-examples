<?php get_header(); ?>

	<section class="header-img">
		<?php 
		$headerImg = get_field('news_background_image', 'option'); ?>
	    <img src="<?php echo $headerImg; ?>" class="img-responsive"/>

	</section>

	<section class="content-container">

	    <div class="side-nav">
	        <?php html5blank_nav(); ?>
	    </div>
	    <div class="main-content">
	    	<div class="content-title">
                <h1><?php _e( 'News', 'html5blank' ); ?></h1>

                <a href="#" class="nav-mobile-toggle sb-toggle-left">
                    <i class="icon-navicon"></i>
                </a>
            </div>
            <div class="content-body">
            	<div class="body-container">
            		<p>Helen Cook - Head of Marketing and Communications – 01252 773720 – <a href="mailto:hcook@woodenspoon.com" target="_blank">hcook@woodenspoon.com</a></p>
					<p>Jenni Musson – Communications Officer – 01252 773720 – <a href="mailto:jmusson@woodenspoon.com" target="_blank">jmusson@woodenspoon.com</a></p>
					<p><strong>FILTER BY CATEGORY</strong></p>
                    <?php echo do_shortcode( '[ULWPQSF id=1211]' ); ?>
				</div>
				
		    	<?php get_template_part('loop'); ?>

			</div>
	    </div>
	</section>



<?php get_footer(); ?>

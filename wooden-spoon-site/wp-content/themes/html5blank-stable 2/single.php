<?php get_header(); ?>

	<section class="header-img">
	    <?php 
		$headerImg = get_field('header_image');
		if( !empty($headerImg) ): ?>
	    	<img src="<?php echo $headerImg; ?>" class="img-responsive"/>
	    <?php else: ?>
			<img src="<?php echo home_url(); ?>/wp-content/uploads/2016/03/0058_Challenges-Events-header-image.png" class="img-responsive"/>
		<?php endif; ?>
	</section>

	<section class="content-container">

	    <div class="side-nav">
	        <?php html5blank_nav(); ?>
	    </div>
	    <div class="main-content">

			<?php if (is_singular('success-stories')) {
				include( get_template_directory() . '/single-event-success.php');
			} ?>

			<?php if(is_singular('region')) {
				include( get_template_directory() . '/single-regions.php');
			}?>

			<?php if(is_singular('post')) {
				include( get_template_directory() . '/single-news.php');
			}?>

			<?php if(is_singular('event')) {
				include( get_template_directory() . '/single-events.php');
			}?>
			
	    </div>
	</section>

<?php get_footer(); ?>

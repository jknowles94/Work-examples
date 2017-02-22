<div class="content-title">
    <h1><?php the_title(); ?></h1>

    <a href="#" class="nav-mobile-toggle sb-toggle-left">
        <i class="icon-navicon"></i>
    </a>
</div>

<div class="content-body">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

	<div class="body-container">
		<div class="row">
			<div class="col-sm-6">
				<?php the_content(); ?>
			</div>
			<div class="col-sm-6">
				<img src="<?php the_field('event_image'); ?>" class="img-responsive">
			</div>
		</div>


	</div>

	<?php endwhile; ?>
	<?php endif; ?>
</div>
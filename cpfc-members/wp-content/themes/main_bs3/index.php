<?php
//Template name: Default
get_header();
?>

<div class="content">
	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<div class="post">

			<!-- Display the Title as a link to the Post's permalink. -->
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

			<!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->
			<small><?php the_time('F jS, Y') ?> by <?php the_author_posts_link() ?></small>

			<div class="entry">
			<?php the_content(); ?>
			</div>

			<p class="postmetadata">Posted in <?php the_category(', '); ?></p>
		</div> <!-- closes the first div box -->

		<?php endwhile; else: ?>
		<p>Sorry, no posts matched your criteria.</p>
		<?php endif; ?>
	</div>
</div>


<!-- Grid Examples -->  
        <div class="fullwidth">
            <div class="container">

                <h1>Bootstrap grid examples</h1>
                <p class="lead">Basic grid layouts to get you familiar with building within the Bootstrap grid system.</p>

                <h3>Three equal columns</h3>
                <p>Get three equal-width columns <strong>starting at desktops and scaling to large desktops</strong>. On mobile devices, tablets and below, the columns will automatically stack.</p>
                <div class="row">
                    <div class="col-md-4">.col-md-4</div>
                    <div class="col-md-4">.col-md-4</div>
                    <div class="col-md-4">.col-md-4</div>
                </div>

                <h3>Three unequal columns</h3>
                <p>Get three columns <strong>starting at desktops and scaling to large desktops</strong> of various widths. Remember, grid columns should add up to twelve for a single horizontal block. More than that, and columns start stacking no matter the viewport.</p>
                <div class="row">
                    <div class="col-md-3">.col-md-3</div>
                    <div class="col-md-6">.col-md-6</div>
                    <div class="col-md-3">.col-md-3</div>
                </div>

                <h3>Two columns</h3>
                <p>Get two columns <strong>starting at desktops and scaling to large desktops</strong>.</p>
                <div class="row">
                <div class="col-md-8">.col-md-8</div>
                <div class="col-md-4">.col-md-4</div>
                </div>

                <h3>Full width, single column</h3>
                <p class="text-warning">No grid classes are necessary for full-width elements.</p>

                <hr>

                <h3>Two columns with two nested columns</h3>
                <p>Per the documentation, nesting is easy&mdash;just put a row of columns within an existing column. This gives you two columns <strong>starting at desktops and scaling to large desktops</strong>, with another two (equal widths) within the larger column.</p>
                <p>At mobile device sizes, tablets and down, these columns and their nested columns will stack.</p>
                <div class="row">
                    <div class="col-md-8">
                    .col-md-8
                        <div class="row">
                            <div class="col-md-6">.col-md-6</div>
                            <div class="col-md-6">.col-md-6</div>
                        </div>
                    </div>
                    <div class="col-md-4">.col-md-4</div>
                </div>

                <hr>

                <h3>Mixed: mobile and desktop</h3>
                <p>The Bootstrap 3 grid system has four tiers of classes: xs (phones), sm (tablets), md (desktops), and lg (larger desktops). You can use nearly any combination of these classes to create more dynamic and flexible layouts.</p>
                <p>Each tier of classes scales up, meaning if you plan on setting the same widths for xs and sm, you only need to specify xs.</p>
                <div class="row">
                    <div class="col-xs-12 col-md-8">.col-xs-12 .col-md-8</div>
                    <div class="col-xs-6 col-md-4">.col-xs-6 .col-md-4</div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-md-4">.col-xs-6 .col-md-4</div>
                    <div class="col-xs-6 col-md-4">.col-xs-6 .col-md-4</div>
                    <div class="col-xs-6 col-md-4">.col-xs-6 .col-md-4</div>
                </div>

                <div class="row">
                    <div class="col-xs-6">.col-xs-6</div>
                    <div class="col-xs-6">.col-xs-6</div>
                </div>

                <hr>

                <h3>Mixed: mobile, tablet, and desktop</h3>
                <p></p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-lg-8">.col-xs-12 .col-sm-6 .col-lg-8</div>
                    <div class="col-xs-6 col-lg-4">.col-xs-6 .col-lg-4</div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-4">.col-xs-6 .col-sm-4</div>
                    <div class="col-xs-6 col-sm-4">.col-xs-6 .col-sm-4</div>
                    <div class="col-xs-6 col-sm-4">.col-xs-6 .col-sm-4</div>
                </div>

                <hr>

                <h3>Column clearing</h3>
                <p><a href="http://getbootstrap.com/css/#grid-responsive-resets">Clear floats</a> at specific breakpoints to prevent awkward wrapping with uneven content.</p>
                <div class="row">
                    <div class="col-xs-6 col-sm-3">
                    .col-xs-6 .col-sm-3
                    <br>
                    Resize your viewport or check it out on your phone for an example.
                    </div>

                    <div class="col-xs-6 col-sm-3">.col-xs-6 .col-sm-3</div>

                    <!-- Add the extra clearfix for only the required viewport -->
                    <div class="clearfix visible-xs"></div>

                    <div class="col-xs-6 col-sm-3">.col-xs-6 .col-sm-3</div>
                    <div class="col-xs-6 col-sm-3">.col-xs-6 .col-sm-3</div>
                </div>

                <hr>

                <h3>Offset, push, and pull resets</h3>
                <p>Reset offsets, pushes, and pulls at specific breakpoints.</p>
                <div class="row">
                    <div class="col-sm-5 col-md-6">.col-sm-5 .col-md-6</div>
                    <div class="col-sm-5 col-sm-offset-2 col-md-6 col-md-offset-0">.col-sm-5 .col-sm-offset-2 .col-md-6 .col-md-offset-0</div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-md-5 col-lg-6">.col-sm-6 .col-md-5 .col-lg-6</div>
                    <div class="col-sm-6 col-md-5 col-md-offset-2 col-lg-6 col-lg-offset-0">.col-sm-6 .col-md-5 .col-md-offset-2 .col-lg-6 .col-lg-offset-0</div>
                </div>

            </div>
        </div>

<?php get_footer(); ?>
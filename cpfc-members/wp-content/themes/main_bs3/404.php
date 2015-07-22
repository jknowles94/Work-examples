<?php
/**
 * 404 page
 */
 
// we don't want to redirect to a 404 on login
\CPFCMembers\Session::delete('login_redirect');
 
get_header(); ?>

<div id="sb-site">

    <?php
    get_partial('partials/cookie_policy');

    if (\CPFCMembers\Auth::isLoggedIn()) {
        get_partial('partials/header_logged_in');
    } else {
        get_partial('partials/header_logged_out', array('showLogin'=>true));
    } ?>

     <!-- 404 -->
    <section class="fullwidth fourzerofour">
        <div class="container">
            <div class="row">

                <div class="col-sm-3 col-sm-offset-1 hidden-xs">
                    <?php $image = get_field('404_error_image', 'option'); ?>
                    <img src="<?php echo isset($image['url']) ? $image['url'] : ''; ?>" alt="<?php echo isset($image['alt']) ? $image['alt'] : ''; ?>" class="img-responsive">
                </div>

                <div class="col-sm-6">
                    <h1><?php echo get_field('404_error_heading', 'option'); ?></h1>

                    <?php echo get_field('404_error_copy', 'option'); ?>
                    <?php
                    $links = get_field('404_error_links', 'option');
                    if ($links) {
                        foreach ($links as $link) { ?>
                        <p class="more"><a href="<?php echo $link['url']; ?>"><?php echo $link['text']; ?> <i class="icon-right-1"></i></a></p>
                        <?php
                        }
                    } ?>
                </div>

            </div>
        </div>
    </section>

<?php get_footer(); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php
    // Print the <title> tag based on what is being viewed.
    global $page, $paged;

    wp_title( '|', true, 'right' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        echo " | $site_description";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
        echo ' | ' . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );

    ?></title>


    <meta name="viewport" content="width=device-width, initial-scale=1">

<?php /* icons handled by plugin
<!--    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png"> -->
    <link rel="shortcut icon" sizes="196x196" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">

    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
*/ ?>
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">
    <!--[if lte IE 8]><link rel="stylesheet" href="../assets/styles/ie.css" /><![endif]-->

    <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/picturefill.js" async></script>
    <script src="<?php echo get_template_directory_uri(); ?>/bower_components/modernizr/modernizr.js"></script>

    <?php wp_head(); ?>
</head>

<body>

    <?php get_partial('partials/google_tag_manager'); ?>
    <?php get_partial('partials/facebook_share'); ?>


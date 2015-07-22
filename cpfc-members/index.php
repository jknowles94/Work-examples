<?php

/*echo json_encode(array(
	'result' => 'success',
	'items' => array(
		array(
			'title' => 'Test Article 1',
			'image' => 'http://blah.jpg',
			'duration' => '2',
			'itemLink' => 'http://google.com'
		),
		array(
			'title' => 'Test Article 2',
			'image' => 'http://blah.jpg',
			'duration' => '2',
			'itemLink' => 'http://google.com'
		),
		array(
			'title' => 'Test Article 3',
			'image' => 'http://blah.jpg',
			'duration' => '2',
			'itemLink' => 'http://google.com'
		),
		array(
			'title' => 'Test Article 4',
			'image' => 'http://blah.jpg',
			'duration' => '2',
			'itemLink' => 'http://google.com'
		),
		array(
			'title' => 'Test Article 5',
			'image' => 'http://blah.jpg',
			'duration' => '2',
			'itemLink' => 'http://google.com'
		),
	),
	'more_link' => true,
));
die();*/


/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require( dirname( __FILE__ ) . '/wp-blog-header.php' );

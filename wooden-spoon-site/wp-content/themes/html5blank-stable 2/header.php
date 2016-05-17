<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' |'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
        <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png">
        <link href='https://fonts.googleapis.com/css?family=Oswald:400,300,700|Roboto+Slab:400,300,100,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bower_components/ThumbnailGrid/css/component.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bower_components/Slidebars/dist/slidebars.min.css">
        

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>

			<div id="accessibility">
			</div>

			<!-- Slidebar -->
		    <div class="sb-slidebar sb-left">
		        <?php html5blank_nav(); ?>
		    </div>
		    <div id="sb-site">
				<!-- header -->
				<header class="fullwidth-np header">
		            <div class="container">
		                <a href="<?php echo home_url(); ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="Wooden Spoon"></a>

		                <!-- <a href="#" class="nav-mobile-toggle">
		                    <i class="icon-navicon"></i>
		                </a> -->

		                <div class="subscribe">

							<form class="form-subscribe" action="https://na27.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8" method="POST">
		                    	<input type=hidden name="oid" value="00D400000008hFR">
								<input type=hidden name="retURL" value="http://woodenspoonstaging.amigopartnership.com/subscribe-thank-you/">
		                        <div class="form-group">
		                            <label for="email">Subscribe to newsletter:</label>
		                            <input type="email" class="form-control" placeholder="Enter email address" name="email">
		                            <button type="submit" name="submit" class="btn-subscribe"><i class="icon-right-open"></i></button>
		                        </div>
		                    </form>
		                </div>

		                <div class="header-nav">
		                    <?php header_nav(); ?>
		                </div>

		                <div class="header-donate">
		                    <a href="/donate/">Donate</a>
		                </div>
		            </div>
		        </header> 
				<!-- /header -->

        <!-- Footer -->
        <footer class="fullwidth-sm footer">
            <div class="container">
                <div class="footer-contain">
                    <?php
                        wp_nav_menu( array(
                        'menu'              => 'footer',
                        'theme_location'    => 'footer',
                        'depth'             => 1,
                        'container'         => '',
                        'container_class'   => '',
                        'menu_class'        => 'nav-footer',
                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                        'walker'            => new wp_bootstrap_navwalker())
                    ); ?>
                    <p class="copyright"><?php echo preg_replace(array('~\(c\)~i','~#year~i'),array('&copy;',date('Y')), get_field('footer_copyright_text','option')); ?></p>
                </div>
            </div>
        </footer>

    </div> <!-- end sb-site -->


      <!-- Mobile slidebar content -->
    <div class="sb-slidebar sb-width-thin sb-right background-brand">
        <div class="close sb-toggle-right">
            <i class="icon-cancel-1"></i>
        </div>

        <nav class="nav">
        	<?php
                wp_nav_menu( array(
                'menu'              => 'header',
                'theme_location'    => 'header',
                'depth'             => 1,
                'container'         => '',
                'container_class'   => '',
                'menu_class'        => '',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new wp_bootstrap_navwalker())
            ); ?>
            <div class="sb-offsite">
        	<?php
                wp_nav_menu( array(
                'menu'              => 'external',
                'theme_location'    => 'external',
                'depth'             => 1,
                'container'         => '',
                'container_class'   => '',
                'menu_class'        => '',
                'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                'walker'            => new wp_bootstrap_navwalker())
            ); ?>
            </div>
            <ul>
                <li><a href="<?php echo cpfc_home_url(CPFC_MEMBERS_UPDATE_DETAILS_URL); ?>">My Details</a></li>
                <li><a href="<?php echo cpfc_home_url(CPFC_MEMBERS_LOGOUT_URL); ?>">Logout</a></li>
            </ul>
        </nav>

    </div>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo get_template_directory_uri(); ?>/bower_components/jquery/dist/jquery.min.js"><\/script>')</script>

    <script type='text/javascript' src="//wurfl.io/wurfl.js"></script>

    <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/plugins.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/main.js"></script>

    <script src="<?php echo get_template_directory_uri(); ?>/assets/scripts/lunametrics-youtube-v7.gtm.min.js"></script>

	<script>
	window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));
	</script>

    </body>
</html>
<div class="row">
    <nav class="nav nav-primary full-width">
        <div class="container">
            <div class="col-lg-9 col-md-8 col-sm-7">
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
            </div>
            <div class="col-lg-3 col-md-4 col-sm-5 offsite">
                <?php
                    wp_nav_menu( array(
                    'menu'              => 'external',
                    'theme_location'    => 'external',
                    'depth'             => 1,
                    'container'         => '',
                    'container_class'   => '',
                    'menu_class'        => 'offsite-inner-wrapper',
                    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                    'walker'            => new wp_bootstrap_navwalker())
                ); ?>
            </div>
        </div>
    </nav>
</div>
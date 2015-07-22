<header class="header logged-in">

    <div class="container">
        <?php
        $logoImage = get_field('header_logo', 'option'); ?>
        <a href="<?php echo cpfc_home_url(); ?>" class="logo"><img src="<?php echo isset($logoImage['url']) ? $logoImage['url'] : ''; ?>" alt="<?php echo isset($logoImage['alt']) ? $logoImage['alt'] : ''; ?>"></a>

        <nav class="login hidden-xs">
            <ul>
                <li class="login-name dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown"><a href="<?php echo cpfc_home_url(CPFC_MEMBERS_UPDATE_DETAILS_URL); ?>">My Details</i></a>
                </li>
                <li class="logout"><a href="<?php echo cpfc_home_url(CPFC_MEMBERS_LOGOUT_URL); ?>">Logout</a></li>
            </ul>
        </nav>

        <div class="burger sb-toggle-right visible-xs">
            <i class="icon-menu"></i>
        </div>
    </div>

    <?php
    get_partial('partials/header_menu'); ?>

</header>
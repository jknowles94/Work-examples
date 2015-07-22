<?php
$form = \CPFCMembers\LoginForm::getInstance(); ?>

<header class="header logged-out">

    <?php
    if (isset($showLogin) && $showLogin) { ?>

    <div class="login-panel">
        <div class="container">
            <form class="login" method="POST" action="<?php echo cpfc_home_url(CPFC_MEMBERS_LOGIN_URL); ?>">
                <?php echo $form->securityField(); ?>
                <fieldset>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-block-wrap form-group">
                                <label for="email_address" class="control-label hidden">Email <?php echo $form->indicateRequired('email_address'); ?></label>
                                <input class="form-control" type="text" name="email_address" id="email" placeholder="Your email" data-bv-notempty="true" data-bv-notempty-message="The email address is required and cannot be empty" data-bv-emailaddress="true" data-bv-emailaddress-message="The email address is not valid" />
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="form-block-wrap form-group">
                                <label for="password" class="control-label hidden">Password <?php echo $form->indicateRequired('password'); ?></label>
                                <input class="form-control" type="password" name="password" id="password" placeholder="Your password" data-bv-notempty="true" data-bv-notempty-message="A password is required and cannot be empty" data-bv-identical="true" data-bv-identical-field="confirmPassword" data-bv-identical-message="Passwords do not match" data-bv-different="true" data-bv-different-field="username" data-bv-different-message="The password cannot be the same as username" />
                                <a href="<?php echo cpfc_home_url(CPFC_MEMBERS_FORGOTTEN_PASSWORD_URL); ?>">Forgotten your password?</a>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="logged">
                                <label for="long-login">Keep me logged in</label>
                                <input type="checkbox" name="long_login" id="long-login"value="1" checked />
                            </div>
                            <div class="form-group submit">
                                <input type="submit" class="btn" name="login_submit" value="Login" />
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <a href="#" class="login-btn"><span>Login</span></a>
        </div>
    </div>

    <?php
    } ?>

    <div class="container">
        <?php
        $logoImage = get_field('header_logo', 'option'); ?>
        <a href="<?php echo cpfc_home_url(); ?>" class="logo"><img src="<?php echo isset($logoImage['url']) ? $logoImage['url'] : ''; ?>" alt="<?php echo isset($logoImage['alt']) ? $logoImage['alt'] : ''; ?>"></a>
    </div>
</header>
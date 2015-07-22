<?php
get_header();

$landingPage = get_page_by_path('landing-page'); ?>



<div id="sb-site" class="bg-gradient">

    <?php
    get_partial('partials/cookie_policy');

    get_partial('partials/header_logged_out', array('showLogin'=>true)); ?>

    <?php
    $fullMainImage = get_field('homepage_logged_out_main_promo_full_image', $landingPage->ID);
    $fullMainImageMobile = get_field('homepage_logged_out_main_promo_full_image_mobile', $landingPage->ID); ?>

        <section class="fullwidth-bg hero" style="background-image:url(<?php echo get_template_directory_uri(); ?>/assets/images/landing-hero-bg.png)">
            <div class="container">

                <a href="<?php the_field('homepage_logged_out_main_promo_full_image_url', $landingPage->ID); ?>">
                    <picture>
                        <!--[if IE 9]><video style="display: none;"><![endif]-->
                        <source srcset="<?php echo isset($fullMainImage['url']) ? $fullMainImage['url'] : ''; ?>" media="(min-width: 480px)" class="img-responsive">
                        <source srcset="<?php echo isset($fullMainImageMobile['url']) ? $fullMainImageMobile['url'] : ''; ?>" class="img-responsive">
                        <!--[if IE 9]></video><![endif]-->
                        <img srcset="<?php echo isset($fullMainImage['url']) ? $fullMainImage['url'] : ''; ?>" alt="<?php echo isset($fullMainImage['alt']) ? $fullMainImage['alt'] : ''; ?>" class="img-responsive">
                    </picture>
                </a>

            </div>
        </section>

        <div class="content logged-out">

            <div class="container">

            <section class="fullwidth content-padding">

                <div class="intro">
                    <?php echo $landingPage->post_content; ?>

                 </div>

                <div class="articles-shorts">
                    <div class="header-contain">
                         <h1 class="heading header-left header-right"><?php the_field('homepage_logged_out_members_benefits_title', $landingPage->ID); ?></h1>
                         <hr />
                    </div>

                    <?php
                    $i = 0;
                    foreach (get_field('homepage_logged_out_members_benefits_benefits_vertical', $landingPage->ID) as $benefitVertical) {
                        if ($i % 2) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="benefits item">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <img src="<?php echo isset($benefitVertical['image']['url']) ? $benefitVertical['image']['url'] : ''; ?>" alt="<?php echo isset($benefitVertical['image']['alt']) ? $benefitVertical['image']['alt'] : ''; ?>" class="img-responsive" />
                                    </div>
                                    <div class="col-sm-6">
                                        <h2><?php echo $benefitVertical['heading']; ?></h2>
                                        <?php echo $benefitVertical['supporting_copy']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
                        } else { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="benefits item">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-push-6">
                                        <img src="<?php echo isset($benefitVertical['image']['url']) ? $benefitVertical['image']['url'] : ''; ?>" alt="<?php echo isset($benefitVertical['image']['alt']) ? $benefitVertical['image']['alt'] : ''; ?>" class="img-responsive" />
                                    </div>
                                    <div class="col-sm-6 col-sm-pull-6">
                                        <h2><?php echo $benefitVertical['heading']; ?></h2>
                                        <?php echo $benefitVertical['supporting_copy']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
                        } ?>

                    <?php
                        $i++;
                    } ?>

                    <div class="row">
                        <?php
                        foreach (get_field('homepage_logged_out_members_benefits_benefits_horizontal', $landingPage->ID) as $benefitHorizontal) { ?>
                        <div class="col-sm-4">
                            <div class="footer-article item">
                                <img src="<?php echo isset($benefitHorizontal['image']['url']) ? $benefitHorizontal['image']['url'] : ''; ?>" alt="<?php echo isset($benefitHorizontal['image']['alt']) ? $benefitHorizontal['image']['alt'] : ''; ?>" class="img-responsive" />
                                <h3><?php echo $benefitHorizontal['heading']; ?></h3>
                                <?php echo $benefitHorizontal['supporting_copy']; ?>
                            </div>
                        </div>
                    <?php
                    } ?>
                    </div>
                </div>

            </section>

        </div>

        <section class="full-width register-promo">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <h3><?php the_field('homepage_logged_out_bottom_promo_heading_cta_text', $landingPage->ID); ?></h3>
                        <a href="<?php the_field('homepage_logged_out_bottom_promo_cta_button_url', $landingPage->ID); ?>" class="btn register"><?php the_field('homepage_logged_out_bottom_promo_cta_button_text', $landingPage->ID); ?> </a>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php
    get_partial('partials/partners'); ?>

<?php get_footer(); ?>
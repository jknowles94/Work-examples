<?php
$page = "thankyou";
require_once "inc/donation-logic.php";
?>
<?php /* Template Name: Donate Thankyou */get_header();?>
<section class="header-img">
  <?php
$headerImg = get_field('header_image');
if (!empty($headerImg)): ?>
    <img src="<?php echo $headerImg; ?>" class="img-responsive" />
    <?php else: ?>
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/header-img.png" class="img-responsive" />
    <?php endif;?>
</section>
<section class="content-container">
  <div class="side-nav">
    <?php html5blank_nav();?>
  </div>
  <div class="main-content">
    <div class="content-title">
      <h1><?php the_title();?></h1>
      <a href="#" class="nav-mobile-toggle sb-toggle-left">
        <i class="icon-navicon"></i>
      </a>
    </div>
    <div class="content-body">
      <div class="body-container">
          <div class="form-pagination"><span><i class="icon-nos-01"></i> Donation</span><span><i class="icon-nos-02"></i> Details</span><span ><i class="icon-nos-03"></i> Payment</span><span class="active"><i class="icon-nos-04"></i> Confirmation</span></div>
          <h3>Confirmation</h3>
          <div class="form-body">
            <p class="thank-title">Thank You!</p>
            <?php if (have_posts()): ?>
            <?php while (have_posts()): ?>
            <?php the_post();?>
            <?php the_content();?>
            <?php endwhile;?>
            <?php endif;?>
            <div class="linebreak"></div>
            <p>Please also encourage your friends and family to do the same</p>
            <nav class="nav nav-social">
              <a href="https://www.facebook.com/WoodenSpoonCharity" target="_blank" class="facebook"><i class="icon-facebook"></i></a>
              <a href="https://twitter.com/CharitySpoon" class="twitter"><i class="icon-twitter"></i></a>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php get_footer();?>

<?php
$page = "details";
require_once "inc/donation-logic.php";
?>
<?php /* Template Name: Donate Details */get_header();?>
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
        <div class="form-pagination"><span><i class="icon-nos-01"></i> Donation</span><span class="active"><i class="icon-nos-02"></i> Details</span><span ><i class="icon-nos-03"></i> Payment</span><span ><i class="icon-nos-04"></i> Confirmation</span></div>
        <?php if (have_posts()): ?>
        <?php while (have_posts()): ?>
        <?php the_post();?>
        <?php the_content();?>
        <?php endwhile;?>
        <?php endif;?>
        <form class="form-donate form-validate details" action="/donate/payment" method="post">
          <h3>Your Details</h3>
          <div class="form-body">
            <p><strong>Name</strong></p>
            <div class="form-group">
              <label for="title">Title<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <select id="title" name="title">
                <option value="mr">Mr</option>
                <option value="mrs">Mrs</option>
                <option value="ms">Ms</option>
                <option value="miss">Miss</option>
              </select>
            </div>
            <div class="form-group">
              <label for="firstName">First Name<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="firstName" id="firstName" data-bv-notempty="true" data-bv-notempty-message="First name is required"></input>
            </div>
            <div class="form-group">
              <label for="lastName">Last Name<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="lastName" id="lastName" data-bv-notempty="true" data-bv-notempty-message="Last name is required"></input>
            </div>
          </div>
          <div class="form-body">
            <p><strong>Email</strong></p>
            <div class="form-group">
              <label for="email">Email<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="email" name="email" id="email" data-bv-notempty="true" data-bv-notempty-message="Email is required" data-bv-emailaddress="true" data-bv-emailaddress-message="Invalid email address"></input>
            </div>
          </div>
          <div class="form-body">
            <p><strong>Address</strong></p>
            <div class="form-group">
              <label for="postcode">Postcode<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="postcode" id="postcode" data-bv-notempty="true" data-bv-notempty-message="Postcode is required"></input>
            </div>
            <div class="form-group">
              <label for="addressLine1">Address Line 1<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="addressLine1" id="addressLine1" data-bv-notempty="true" data-bv-notempty-message="Address is required"></input>
            </div>
            <div class="form-group">
              <label for="addressLine2">Address Line 2</a></label>
              <input type="text" name="addressLine2" id="addressLine2"></input>
            </div>
            <div class="form-group">
              <label for="town">Town or City<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="town" id="town" data-bv-notempty="true" data-bv-notempty-message="Town or City is required"></input>
            </div>
            <div class="form-group">
              <label for="country">Country</label>
              <input type="text" name="country" id="country" data-bv-notempty-message="Country is required"></input>
            </div>
          </div>
          <div class="form-body">
            <p><strong>Phone Number</strong></p>
            <div class="form-group">
              <label for="phone">Phone Number<a href="javavascript:void(0);" title="Mandatory field">&nbsp;*&nbsp;</a></label>
              <input type="text" name="phone" id="phone" data-bv-notempty="true" data-bv-notempty-message="Phone number is required" data-bv-phone-country="GB" data-bv-phone-message="Please enter a valid phone number"></input>
            </div>
          </div>
          <div class="form-body">
            <p><strong>Gift Aid</strong></p>
            <p>If you are a UK taxpayer please select yes so we can recaive up to 25p for every £1 you give at no extra cost to you.</p>
            <div class="form-group">
              <div class="checkbox">
                <input type="checkbox" name="giftAid" value="true"><span><strong>Yes</strong>, I am a UK taxpayer and would like Wooden Spoon to reclaim the tax on this donation, any of the donations I have made in the last four years and future donations I may make.</span>
              </div>
            </div>
          </div>
          <div class="form-body">
            <p><strong>Data protection info</strong></p>
            <p>Wooden Spoon values your support and promises to respect your privacy. We would love to keep you updated about our work and how your support is changing lives, and we do not share any of your details with third parties.</p>
            <div class="form-group">
              <div class="checkbox">
                <input type="checkbox" name="dataProtection" value="true"><span>If you’re happy to receive information from us via email please tick the box:</span>
              </div>
            </div>
          </div>
          <div class="form-body">
            <p><strong>*</strong> Mandatory field.</p>
          </div>
          <button type="submit" class="btn-submit">Continue</button>
        </form>
      </div>
    </div>
  </div>
</section>
<?php get_footer();?>

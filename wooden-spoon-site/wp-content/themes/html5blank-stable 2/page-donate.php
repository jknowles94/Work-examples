<?php session_destroy();?>

<?php /* Template Name: Donate */get_header();?>
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
        <div class="form-pagination"><span class="active"><i class="icon-nos-01"></i> Donation</span><span ><i class="icon-nos-02"></i> Details</span><span ><i class="icon-nos-03"></i> Payment</span><span ><i class="icon-nos-04"></i> Confirmation</span></div>
        <?php if (have_posts()): ?>
        <?php while (have_posts()): ?>
        <?php the_post();?>
        <?php the_content();?>
        <?php endwhile;?>
        <?php endif;?>
        <form class="form-donate form-validate" action="/donate/details" method="post">
          <h3>Donation</h3>
          <div class="form-body">
            <p><strong>Amount</strong></p>
            <div class="form-group">
              <label for="donationAmount">I would like to give</label>

              <div id="donationAmountSelectContainer">
                <select name="donationAmount" id="donationAmountSelect" data-bv-notempty="true" data-bv-notempty-message="Donation Amount is required">
                  <option value="10">£10</option>
                  <option value="20">£20</option>
                  <option value="40" selected>£40</option>
                  <option value="other">Other...</option>
                </select>
              </div>
              <div id="donationAmountInputContainer" style="display: none;">
                £<input name="donationAmount" id="donationAmountInput" data-bv-notempty="true" data-bv-notempty-message="Donation Amount is required" disabled="true">
              </div>

            </div>
            <div class="row">
              <div class="form-group col-sm-7">
                <div class="row">
                  <div class="col-md-4">
                    <label>Every:</label>
                  </div>
                  <div class="col-md-8">
                    <div class="checkbox">
                      <input type="radio" id="donationRepetition-month" name="donationRepetition" value="month"><label for="donationRepetition-month"><span>Month</span></label>
                    </div>
                    <div class="checkbox">
                      <input type="radio" id="donationRepetition-year" name="donationRepetition" value="year"><label for="donationRepetition-year"><span>Year</span></label>
                    </div>
                    <div class="checkbox">
                      <input type="radio" id="donationRepetition-oneoff" name="donationRepetition" value="oneoff"><label for="donationRepetition-oneoff"><span>One-off donation</span></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <p>Donating at least £40 per year will automatically make you become a member of Wooden Spoon</p>
          </div>

          <div class="form-body">
            <textarea name="comments" id="comments" placeholder="Payment reference e.g. fundraiser name, event details or membership number"></textarea>
          </div>
          <button type="submit" class="btn-submit">Continue</button>
        </form>
      </div>
    </div>
  </div>
</section>
<?php get_footer();?>

<script type="text/javascript">
  jQuery("#donationAmountSelect").change(
    function(e){
      if("other" == e.target.value){
        jQuery("#donationAmountSelectContainer").hide();
        jQuery("#donationAmountSelect").prop("disabled", true);
        jQuery("#donationAmountInputContainer").show();
        jQuery("#donationAmountInput").prop("disabled", false);
      }
  }
  );
</script>


<?php
$page = "payment";
require_once "inc/donation-logic.php";
?>

<?php /* Template Name: Donate Payment */get_header();?>
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
        <div class="form-pagination"><span><i class="icon-nos-01"></i> Donation</span><span><i class="icon-nos-02"></i> Details</span><span class="active"><i class="icon-nos-03"></i> Payment</span><span><i class="icon-nos-04"></i> Confirmation</span></div>
        <?php if (have_posts()): ?>
        <?php while (have_posts()): ?>
        <?php the_post();?>
        <?php the_content();?>
        <?php endwhile;?>
        <?php endif;?>
        <h3>Payment</h3>

        <!-- <div class="form-body">
          <p><strong>Gift Aid</strong></p>
          <p>If you are a UK taxpayer please select yes so we can recaive up to 25p for every £1 you give at no extra cost to you.</p>
          <div class="form-group">
            <div class="checkbox">
              <input type="checkbox" name="giftAid" value="true"><span><strong>Yes</strong>, I am a UK taxpayer and would like Wooden Spoon to reclaim the tax on this donation, any of the donations I have made in the last four years and future donations I may make.</span>
            </div>
          </div>
        </div> -->

        <?php if (true == $_SESSION["hasError"]): ?>
        <div class="error form-body">
          <?php echo ($_SESSION["error"]); ?>
        </div>
        <br>
        <?php endif?>

          <?php if ($_SESSION["donationRepetition"] != "oneoff"): ?>
            <form class="form-validate form-donate details" method="POST" action="/donate/thankyou">
              <div class="form-body">
              <p><strong>Direct Debits</strong></p>
              <div class="form-group">
                <label for="accountName">Account Holders Name</label>
                <input type="text" name="accountName" id="accountName" data-bv-notempty="true" data-bv-notempty-message="Account Holders Name is required"></input>
              </div>
              <div class="form-group">
                <label for="sortCode">Sort Code</label>
                <input data-bv-notempty="true" data-bv-notempty-message="Sort Code is required" pattern="^[0-9]{2}-[0-9]{2}-[0-9]{2}$" data-bv-regexp-message="Sort Code must be in format nn-nn-nn" id="sortCode" name="sortCode" type="text" ></input>
              </div>
              <div class="form-group">
                <label for="accountNumber">Account Number</label>
                <input data-bv-notempty="true"  data-bv-notempty-message="Account Number is required" pattern="^[0-9]{8}$" data-bv-message="Account Number must be 8 characters long and in number format" id="accountNumber" name="accountNumber"></input>
              </div>
              <div class="dirct-guarant" style="margin: 10px;">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/directdebit_logo.png" style=" display: block; margin: 10px;">
                <a href="https://www.directdebit.co.uk/DirectDebitExplained/pages/directdebitguarantee.aspx" style="font-size: 11px;" target="_blank">See the Direct Debit Guarantee   ›</a>
              </div>
              <button type="submit" class="btn-submit" style="margin:0;">Confirm Donation</button>
            </div>
          </form>
        <?php endif?>
        <div class="form-body">
          <p><strong>Payment</strong></p>
          <form name="contactForm" id="contactForm" method="post" action="https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx" target="_self">
            <input type="hidden" name="HashDigest" value="<?php echo $hash_digest; ?>" />
            <input type="hidden" name="MerchantID" value="<?php echo $merchant_id; ?>" />
            <input type="hidden" name="Amount" value="<?php echo $amount_credit_card; ?>" />
            <input type="hidden" name="CurrencyCode" value="<?php echo $currency_code; ?>" />
            <input type="hidden" name="EchoAVSCheckResult" value="true" />
            <input type="hidden" name="EchoCV2CheckResult" value="true" />
            <input type="hidden" name="EchoThreeDSecureAuthenticationCheckResult" value="true" />
            <input type="hidden" name="EchoCardType" value="true" />
            <input type="hidden" name="OrderID" value="<?php echo $order_id; ?>" />
            <input type="hidden" name="TransactionType" value="SALE" />
            <input type="hidden" name="TransactionDateTime" value="<?php echo $transaction_date_time; ?>" />
            <input type="hidden" name="CallbackURL" value="<?php echo $callback_url_credit_card; ?>" />
            <input type="hidden" name="OrderDescription" value="<?php echo $order_desc; ?>" />
            <input type="hidden" name="CustomerName" value="<?php echo $customer_name; ?>" />
            <input type="hidden" name="Address1" value="<?php echo $address_line1; ?>" />
            <input type="hidden" name="Address2" value="<?php echo $address_line2; ?>" />
            <input type="hidden" name="Address3" value="<?php echo $address_line3; ?>" />
            <input type="hidden" name="Address4" value="<?php echo $address_line4; ?>" />
            <input type="hidden" name="City" value="<?php echo $city; ?>" />
            <input type="hidden" name="State" value="<?php echo $state; ?>" />
            <input type="hidden" name="PostCode" value="<?php echo $postcode; ?>" />
            <input type="hidden" name="CountryCode" value="<?php echo $currency_code; ?>" />
            <input type="hidden" name="EmailAddress" value="<?php echo $email_address; ?>" />
            <input type="hidden" name="PhoneNumber" value="<?php echo $phone_number; ?>" />
            <input type="hidden" name="EmailAddressEditable" value="false" />
            <input type="hidden" name="PhoneNumberEditable" value="false" />
            <input type="hidden" name="CV2Mandatory" value="true" />
            <input type="hidden" name="Address1Mandatory" value="true" />
            <input type="hidden" name="CityMandatory" value="true" />
            <input type="hidden" name="PostCodeMandatory" value="true" />
            <input type="hidden" name="StateMandatory" value="true" />
            <input type="hidden" name="CountryMandatory" value="true" />
            <input type="hidden" name="ResultDeliveryMethod" value="SERVER" />
            <input type="hidden" name="ServerResultURL" value="<?php echo $server_result_url; ?>" />
            <input type="hidden" name="PaymentFormDisplaysResult" value="false" />
            <input type="hidden" name="ServerResultURLCookieVariables" value="" />
            <input type="hidden" name="ServerResultURLFormVariables" value="" />
            <input type="hidden" name="ServerResultURLQueryStringVariables" value="" />
            <input type="hidden" name="ThreeDSecureCompatMode" value="false" />
            <input type="hidden" name="ServerResultCompatMode" value="false" />
            <div class="donateContinue">
              <div class="CreditCard fLeft">
                <p><strong>Pay by Credit/Debit card</strong></p>
                <p><img src="<?php echo get_template_directory_uri(); ?>/assets/images/cards_logo.png" alt="Wooden Spoon"></p>
                <p>
                  <div class="Paybtn fRight">
                    <input type="submit" name="btnSubmit" id="btnSubmit" class="btn-submit" value="Donate" class="confirmDonat" />
                  </div>
                </p>
              </div>
            </div>
            <!-- continueBtn ends -->
          </form>
          <p>OR</p>
          <div class="donateContinue">
              <div class="CreditCard fLeft">
                <p><strong>Pay by PayPal</strong></p>
                <div class="Paybtn fRight">
                  <form action=<?php echo $paypal_form_url ?> method="post" target="_self">
                    <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="business" value="finance@woodenspoon.com">
                    <input type="hidden" name="lc" value="GB">
                    <input type="hidden" name="item_name" value="Wooden Spoon Donation">
                    <input type="hidden" name="amount" value="<?php print $amount_other;?>">
                    <input type="hidden" name="currency_code" value="GBP">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="return" value="<?php print $callback_url_pay_pal;?>" />
                    <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                    <input type="hidden" name="first_name" value="<?php echo $first_name; ?>">
                    <input type="hidden" name="last_name" value="<?php echo $last_name; ?>">
                    <input type="hidden" name="address1" value="<?php echo $address_line1; ?>">
                    <input type="hidden" name="address2" value="<?php echo $address_line2; ?>">
                    <input type="hidden" name="city" value="<?php echo $city; ?>">
                    <input type="hidden" name="state" value="<?php echo $state; ?>">
                    <input type="hidden" name="zip" value="<?php echo $postcode; ?>">
                    <input type="hidden" name="email" value="<?php echo $email_address; ?>">
                    <p><img src="<?php echo get_template_directory_uri(); ?>/assets/images/paypal_logo.png" alt="Wooden Spoon"></p>
                    <p>
                      <div class="Paybtn fRight">
                        <input type="submit" name="btnSubmit" id="btnSubmit" class="btn-submit" value="Donate" class="confirmDonat" />
                      </div>
                    </p>
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                  </form>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  </div>
</section>
<?php get_footer();?>

<?php

$debug = true;
$web_address = get_site_url();
$email_result = TRUE;
$pre_shared_key = get_custom("worldpay_pre_shared_key");

if (WORLDPAY_SANDBOX === true) {
  $from_address = get_custom("worldpay_confirmation_email_from_sandbox");
  $merchant_id = get_custom("worldpay_merchant_id_sandbox");
  $password = get_custom("worldpay_password_sandbox");
  $to_address = get_custom("worldpay_confirmation_email_to_sandbox");
} else {
  $from_address = get_custom("worldpay_confirmation_email_from");
  $merchant_id = get_custom("worldpay_merchant_id");
  $password = get_custom("worldpay_password");
  $to_address = get_custom("worldpay_confirmation_email_to");
}

function create_hash($pre_shared_key, $password) {
  $str = "PreSharedKey=" . $pre_shared_key;
  $str = $str . "&MerchantID=" . $_GET["MerchantID"];
  $str = $str . "&Password=" . $password;
  $str = $str . "&CrossReference=" . $_GET["CrossReference"];
  $str = $str . "&OrderID={" . $_GET["OrderID"] . "}";
  return sha1($str);
}

function check_hash($pre_shared_key, $password) {
  $str1 = $_GET["HashDigest"];
  $hashcode = create_hash($pre_shared_key, $password);
  if ($hashcode == $str1) {
    return "HASH PASSED";
  } else {
    return "HASH FAILED";
  }
}

function guid() {
  if (function_exists("com_create_guid")) {
    return com_create_guid();
  } else {
    mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45); // "-"
    $uuid = chr(123) . // "{"
    substr($charid, 0, 8) . $hyphen .
    substr($charid, 8, 4) . $hyphen .
    substr($charid, 12, 4) . $hyphen .
    substr($charid, 16, 4) . $hyphen .
    substr($charid, 20, 12) .
    chr(125); // "}"
    return $uuid;
  }
}

function hash_error_email() {

  $subject = $web_address . "Cardsave CheckHash not passed";

  $messagecont = "";
  $messagecont .= "<p>\n";
  $messagecont .= "  <b>Date Sent:</b>" . date('jS F Y - g:ia') . "\n";
  $messagecont .= "</p>";

  foreach ($_GET as $key => $value) {
    $messagecont .= "<p>\n";
    $messagecont .= "  <b>" . $key . ":</b><br />";
    $messagecont .= "  " . $value . "\n";
    $messagecont .= "</p>\n";
  }

  $messagecont .= "<p>\n";
  $messagecont .= "  <b>HashDigest</b><br />\n";
  $messagecont .= "  " . $_SESSION["HashDigest"] . "\n";
  $messagecont .= "</p>\n";

  $messagecont .= "<p>\n";
  $messagecont .= "  <b>CreateHash</b><br />\n";
  $messagecont .= "  " . $_SESSION["CreateHash"] . "\n";
  $messagecont .= "</p>\n";

  $messagecont .= "<p>\n";
  $messagecont .= "  <b>CheckHash</b><br />\n";
  $messagecont .= "  " . $_SESSION["CheckHash"] . "\n";
  $messagecont .= "</p>\n";

  $headers = "";
  $headers .= "From: " . $from_address . "\n";
  $headers .= "Content-type: text/html\n";

  wp_mail($to_address, $subject, $messagecont, $headers);
}

function email_table_row($field, $value) {
  $row = "";
  $row .= "  <tr>\n";
  $row .= "    <td style=\"border: 1px solid black;\">" . $field . "</td>\n";
  $row .= "    <td style=\"border: 1px solid black;\">" . $value . "</td>\n";
  $row .= "  </tr>\n";
  return $row;
}

function email_table_header() {
  $header = "";
  $header .= "  <tr>\n";
  $header .= "    <th style=\"border: 1px solid black;\">Field</th>\n";
  $header .= "    <th style=\"border: 1px solid black;\">Value</th>\n";
  $header .= "  </tr>\n";
  return $header;
}

function email_table_caption($caption) {
  return "<caption style=\"border: 1px solid black;\">" . $caption . "</caption>\n";
}

function salesforce_error_mail(
  $to_address,
  $account_holder_name = null,
  $account_sort_code = null,
  $account_number = null,
  $createResponse = null,
  $createResponse1 = null,
  $createResponse2 = null
) {

  $subject = "Salesforce Salesforce Error";

  $messagecont = "";

  $messagecont .= "<p>\n";
  $messagecont .= "  <b>Date Sent:</b>" . date('jS F Y - g:ia') . "\n";
  $messagecont .= "</p>\n";
  $messagecont .= "<table style=\"border: 1px solid black;\">\n";
  $messagecont .= email_table_caption("Contact");
  $messagecont .= email_table_header();
  $messagecont .= email_table_row("Email", $_SESSION["email"]);
  $messagecont .= email_table_row("FirstName", $_SESSION["firstName"]);
  $messagecont .= email_table_row("LastName", $_SESSION["lastName"]);
  $messagecont .= email_table_row("MobilePhone", $_SESSION["phone"]);
  $messagecont .= email_table_row("Salutation", $_SESSION["title"]);
  $messagecont .= email_table_row("MailingCity", $_SESSION["town"]);
  $messagecont .= email_table_row("MailingCountry", $_SESSION["country"]);
  $messagecont .= email_table_row("MailingPostalCode", $_SESSION["postcode"]);
  $messagecont .= email_table_row("MailingStreet", $_SESSION["addressLine1"] . " " . $_SESSION["addressLine2"]);
  $messagecont .= email_table_row("Description", $_SESSION["comments"]);
  $messagecont .= email_table_row("Data_Protection_Received__c", $_SESSION["dataProtection"]);
  $messagecont .= email_table_row("Date_Relationship_Begun__c", date("Y-m-d"));
  if ($_SESSION["giftAid"] == "true") {
    $messagecont .= email_table_row("Said_Yes_to_Gift_Aid__c", "Yes");
  } else {
    $messagecont .= email_table_row("Not_eligible_for_Gift_Aid__c", "Yes");
  }
  $messagecont .= "</table>\n";

  $messagecont .= "<table style=\"border: 1px solid black;\">\n";
  $messagecont .= email_table_header();
  $messagecont .= email_table_caption("Membership");
  $messagecont .= email_table_row("Date_Membership_From__c", date("Y-m-d"));
  if ($_SESSION["donationRepetition"] == "oneoff") {
    $messagecont .= email_table_row("Membership_Status__c", "Non Member");
  } else {
    $messagecont .= email_table_row("Membership_Status__c", "Member");
  }
  $messagecont .= "</table>\n";

  $messagecont .= "<table style=\"border: 1px solid black;\">\n";
  $messagecont .= email_table_caption("Payment");
  $messagecont .= email_table_header();
  $messagecont .= email_table_row("Payment_Method__c", $_SESSION["paymentMethod"]);
  if ($_SESSION["paymentMethod"] == "Direct Debit") {
    $messagecont .= email_table_row("Bank_Account_Name__c", $account_holder_name);
    $messagecont .= email_table_row("Sort_Code__c", $account_sort_code);
    $messagecont .= email_table_row("Account_Number__c", $account_number);
    $messagecont .= email_table_row("First_Collection_Amount__c", $_SESSION["donationAmountOther"]);
    $messagecont .= email_table_row("Payment_Type__c", "Membership Payment");
  } else {
    $messagecont .= email_table_row("RecordTypeId", "01240000000DjRYAA0");
    $messagecont .= email_table_row("Gift_Amount__c", $_SESSION["donationAmountOther"]);
    if ($_SESSION["donationRepetition"] == "oneoff") {
      $messagecont .= email_table_row("Payment_Status__c", "One-off Payment");
      $messagecont .= email_table_row("Collection_Frequency__c", "One-off Payment");
      $messagecont .= email_table_row("Annual_Reminder__c", date("F"));
    } else {
      $messagecont .= email_table_row("Payment_Status__c", "Ongoing Collection");
      $messagecont .= email_table_row("Payment_Type__c", "Membership Payment");
      if ($_SESSION["donationRepetition"] == "year") {
        $messagecont .= email_table_row("Collection_Frequency__c", "Annually");
      }
      if ($_SESSION["donationRepetition"] == "month") {
        $messagecont .= email_table_row("Collection_Frequency__c", "Monthly");
      }
    }
  }
  $messagecont .= "</table>\n";

  if ($createResponse != null) {
    $messagecont .= "<pre>";
    ob_start();
    var_dump($createResponse);
    $messagecont .= ob_get_clean();
    $messagecont .= "</pre>";
  }

  if ($createResponse1 != null) {
    $messagecont .= "<pre>";
    ob_start();
    var_dump($createResponse1);
    $messagecont .= ob_get_clean();
    $messagecont .= "</pre>";
  }

  if ($createResponse2 != null) {
    $messagecont .= "<pre>";
    ob_start();
    var_dump($createResponse2);
    $messagecont .= ob_get_clean();
    $messagecont .= "</pre>";
  }
  $headers = "";
  $headers .= "From: " . $from_address . "\n";
  $headers .= "Content-type: text/plain; charset=UTF-8\n";

  wp_mail($to_address, $subject, $messagecont, $headers);
}

function export_to_saleforce(
  $to_address,
  $account_holder_name = null,
  $account_sort_code = null,
  $account_number = null
) {

  require_once "salesforce-api/soapclient/SforceEnterpriseClient.php";

  try {
    // get a salesforce connection object instance
    $my_sforce_connection = new SforceEnterpriseClient();

    if (SALESFORCE_SANDBOX === true) {
      $from_address = get_custom("worldpay_confirmation_email_from_sandbox");
      $salesforce_password = get_custom("salesforce_password_sandbox");
      $salesforce_security_token = get_custom("salesforce_security_token_sandbox");
      $salesforce_username = get_custom("salesforce_username_sandbox");
      $my_sforce_connection->createConnection(dirname(__FILE__) . "/enterprise_staging.wsdl.xml");
    } else {
      $from_address = get_custom("worldpay_confirmation_email_from");
      $salesforce_password = get_custom("salesforce_password");
      $salesforce_security_token = get_custom("salesforce_security_token");
      $salesforce_username = get_custom("salesforce_username");
      $my_sforce_connection->createConnection(dirname(__FILE__) . "/enterprise_main.wsdl.xml");
    }
    // fill in the definition of the SF system we are connecting
    // Woodenspoon will need to supply an up to date enterprise WSDL
    $ini = ini_set("soap.wsdl_cache_enabled", "0");

    // from userAuth.php or somewhere else
    $my_sforce_connection->login(
      $salesforce_username,
      $salesforce_password . $salesforce_security_token
    );

    $s_contact_obj = new stdclass();
    $s_member_obj = new stdclass();
    $s_direct_deb_obj = new stdclass();

    /*Contact Entry*/
    $s_contact_obj->Email = $_SESSION["email"]; //Monthly
    $s_contact_obj->FirstName = $_SESSION["firstName"];
    $s_contact_obj->LastName = $_SESSION["lastName"];
    $s_contact_obj->MobilePhone = $_SESSION["phone"];
    $s_contact_obj->Salutation = $_SESSION["title"];
    // $s_contact_obj->HomePhone = $_SESSION["home-phone"];

    // if ($_SESSION["date_of_birth"] != "--") { $s_contact_obj->Birthdate = $_SESSION["date_of_birth"]; }
    $s_contact_obj->MailingCity = $_SESSION["town"];
    $s_contact_obj->MailingCountry = $_SESSION["country"];
    $s_contact_obj->MailingPostalCode = $_SESSION["postcode"];
    $s_contact_obj->MailingStreet = $_SESSION["addressLine1"] . " " . $_SESSION["addressLine2"];
    $s_contact_obj->Description = $_SESSION["comments"];
    if ($_SESSION["dataProtection"]) {
      $s_contact_obj->Data_Protection_Received__c = 1;
    }
    $s_contact_obj->Date_Relationship_Begun__c = date("Y-m-d");

    // $s_contact_obj->Primary_Address_Type__c = $_SESSION["address_type"];
    if ($_SESSION["giftAid"] == "true") {
      $s_contact_obj->Said_Yes_to_Gift_Aid__c = 1; //Monthly
    } else {
      $s_contact_obj->Not_eligible_for_Gift_Aid__c = 1; //Monthly
    }
    // create the SF record. This process returns the unique SF id of the record
    // there is NO checking for duplicates here. This is performed in SF.
    $createResponse2 = $my_sforce_connection->create(array($s_contact_obj), "Contact");

    /*Membership Entry*/
    // this field links the just created contact (the id) to the about to be created membership entry
    $s_member_obj->Members_Name__c = $createResponse2[0]->id;

    $s_member_obj->Date_Membership_From__c = date("Y-m-d"); //"2014-08-14";
    // $s_member_obj->Stirred_Into_Action_By__c = $_SESSION["hear_about_us"];

    if ($_SESSION["donationRepetition"] == "oneoff") {
      $s_member_obj->Membership_Status__c = "Non Member";
    } else {
      $s_member_obj->Membership_Status__c = "Member";
    }
    $createResponse1 = $my_sforce_connection->create(array($s_member_obj), "Membership__c");

    /*
    the next two bits of code NEED logic to be placed around them
    I do not know how you are going to decide if this is a direct debit
    or non direct debit money.

    You will need to put an IF ELSE around the next two blocks of code

    Remember these field format values MUST be validated earlier at input
    by the customer

     */
    $s_direct_deb_obj->Payment_Method__c = $_SESSION["paymentMethod"];
    if ($_SESSION["paymentMethod"] == "Direct Debit") {
      /* BLOCK 1 Direct Debit Entry*/
      $s_direct_deb_obj->Bank_Account_Name__c = $account_holder_name;
      $s_direct_deb_obj->Sort_Code__c = $account_sort_code;
      $s_direct_deb_obj->Account_Number__c = $account_number;
      $s_direct_deb_obj->First_Collection_Amount__c = $_SESSION["donationAmountOther"];
      $s_direct_deb_obj->Payment_Type__c = "Membership Payment"; //Monthly
    } else {
      /* BLOCK2 Non-Direct Debit Entry*/
      $s_direct_deb_obj->RecordTypeId = "01240000000DjRYAA0";
      $s_direct_deb_obj->Gift_Amount__c = $_SESSION["donationAmountOther"];
    }

    //Monthly

    if ($_SESSION["donationRepetition"] == "oneoff") {
      $s_direct_deb_obj->Payment_Status__c = "One-off Payment";
      $s_direct_deb_obj->Collection_Frequency__c = "One-off Payment";
      $s_direct_deb_obj->Annual_Reminder__c = date("F");
    } else {
      $s_direct_deb_obj->Payment_Status__c = "Ongoing Collection";
      $s_direct_deb_obj->Payment_Type__c = "Membership Payment";
      if ($_SESSION["donationRepetition"] == "year") {
        $s_direct_deb_obj->Collection_Frequency__c = "Annually";
      }
      if ($_SESSION["donationRepetition"] == "month") {
        $s_direct_deb_obj->Collection_Frequency__c = "Monthly";
      }
    }
    /* end of block 2 */

    /* for both types of collection this bit of code actually creates the record in SF */

    // create the direct debit record in SF - not truely a DD always
    $s_direct_deb_obj->Contact__c = $createResponse2[0]->id;
    $s_direct_deb_obj->Membership__c = $createResponse1[0]->id;
    $createResponse = $my_sforce_connection->create(array($s_direct_deb_obj), "Direct_Debit__c");

    if ($createResponse[0]->success != 1 || $createResponse2[0]->success != 1 || $createResponse1[0]->success != 1) {
      salesforce_error_mail($to_address, $account_holder_name, $account_sort_code, $account_number, $createResponse, $createResponse2, $createResponse1);
    }

  } catch (Exception $e) {
    salesforce_error_mail($to_address, $account_holder_name, $account_sort_code, $account_number, bull, null, null);
  }
  session_destroy();
}

switch ($page) {
case "details":
  if (!isset($_POST["donationAmount"]) && $debug != true) {
    $location = $web_address . "/donate";
    wp_redirect($location);
    exit;
  }

  if (isset($_POST["donationAmount"])) {$_SESSION["donationAmountCreditCard"] = $_POST["donationAmount"] * 100;}
  if (isset($_POST["donationAmount"])) {$_SESSION["donationAmountOther"] = $_POST["donationAmount"];}
  if (isset($_POST["donationRepetition"])) {$_SESSION["donationRepetition"] = $_POST["donationRepetition"];}
  if (isset($_POST["comments"])) {$_SESSION["comments"] = $_POST["comments"];}
  break;
case "payment":
  if (!isset($_SESSION["donationAmount"]) && (!isset($_POST["firstName"]) || !isset($_SESSION["firstName"])) && $debug != true) {
    $location = $web_address . "/donate";
    wp_redirect($location);
    exit;
  }

  if (isset($_POST["addressLine1"])) {$_SESSION["addressLine1"] = $_POST["addressLine1"];}
  if (isset($_POST["addressLine2"])) {$_SESSION["addressLine2"] = $_POST["addressLine2"];}
  if (isset($_POST["country"])) {$_SESSION["country"] = $_POST["country"];}
  if (isset($_POST["dataProtection"])) {$_SESSION["dataProtection"] = $_POST["dataProtection"];}
  if (isset($_POST["email"])) {$_SESSION["email"] = $_POST["email"];}
  if (isset($_POST["firstName"])) {$_SESSION["firstName"] = $_POST["firstName"];}
  if (isset($_POST["giftAid"])) {$_SESSION["giftAid"] = $_POST["giftAid"];}
  if (isset($_POST["lastName"])) {$_SESSION["lastName"] = $_POST["lastName"];}
  if (isset($_POST["phone"])) {$_SESSION["phone"] = $_POST["phone"];}
  if (isset($_POST["postcode"])) {$_SESSION["postcode"] = $_POST["postcode"];}
  if (isset($_POST["title"])) {$_SESSION["title"] = $_POST["title"];}
  if (isset($_POST["town"])) {$_SESSION["town"] = $_POST["town"];}

  $city = $_SESSION["town"];
  $state = $_SESSION["country"];
  $postcode = $_SESSION["postcode"];
  $phone_number = $_SESSION["phone"];
  $customer_name = $_SESSION["firstName"] . " " . $_SESSION["lastName"];
  $first_name = $_SESSION["firstName"];
  $last_name = $_SESSION["lastName"];
  $address_line1 = $_SESSION["addressLine1"];
  $address_line2 = $_SESSION["addressLine2"];
  $address_line3 = $_SESSION["addressLine3"];
  $address_line4 = $_SESSION["addressLine4"];
  $email_address = $_SESSION["email"];

  function gateway_datetime() {
    $str = date("Y-m-d H:i:s O");
    return $str;
  }
  $amount_credit_card = $_SESSION["donationAmountCreditCard"];
  $amount_other = $_SESSION["donationAmountOther"];
  $currency_code = "826";
  $order_id = guid();
  $transaction_date_time = gateway_datetime();

  if (PAYPAL_SANDBOX === true) {
    $paypal_form_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
  } else {
    $paypal_form_url = "https://www.paypal.com/cgi-bin/webscr";
  }

  $callback_url_credit_card = $web_address . "/donate/thankyou";
  $callback_url_pay_pal = $web_address . "/donate/thankyou?paymentMethod=pay_pal";
  $server_result_url = get_bloginfo("template_directory") . "/inc/PaymentFormHostedResult.php";
  $order_desc = "Wooden Spoon Donation";

  $hash_string = "PreSharedKey=" . $pre_shared_key;
  $hash_string .= "&MerchantID=" . $merchant_id;
  $hash_string .= "&Password=" . $password;
  $hash_string .= "&Amount=" . $amount_credit_card;
  $hash_string .= "&CurrencyCode=" . $currency_code;
  $hash_string .= "&EchoAVSCheckResult=" . "true";
  $hash_string .= "&EchoCV2CheckResult=" . "true";
  $hash_string .= "&EchoThreeDSecureAuthenticationCheckResult=" . "true";
  $hash_string .= "&EchoCardType=" . "true";
  $hash_string .= "&OrderID=" . $order_id;
  $hash_string .= "&TransactionType=" . "SALE";
  $hash_string .= "&TransactionDateTime=" . $transaction_date_time;
  $hash_string .= "&CallbackURL=" . $callback_url_credit_card;
  $hash_string .= "&OrderDescription=" . $order_desc;
  $hash_string .= "&CustomerName=" . $customer_name;
  $hash_string .= "&Address1=" . $address_line1;
  $hash_string .= "&Address2=" . $address_line2;
  $hash_string .= "&Address3=" . $address_line3;
  $hash_string .= "&Address4=" . $address_line4;
  $hash_string .= "&City=" . $city;
  $hash_string .= "&State=" . $state;
  $hash_string .= "&PostCode=" . $postcode;
  $hash_string .= "&CountryCode=" . $currency_code;
  $hash_string .= "&EmailAddress=" . $email_address;
  $hash_string .= "&PhoneNumber=" . $phone_number;
  $hash_string .= "&EmailAddressEditable=" . "false";
  $hash_string .= "&PhoneNumberEditable=" . "false";
  $hash_string .= "&CV2Mandatory=" . "true";
  $hash_string .= "&Address1Mandatory=" . "true";
  $hash_string .= "&CityMandatory=" . "true";
  $hash_string .= "&PostCodeMandatory=" . "true";
  $hash_string .= "&StateMandatory=" . "true";
  $hash_string .= "&CountryMandatory=" . "true";
  $hash_string .= "&ResultDeliveryMethod=" . "SERVER";
  $hash_string .= "&ServerResultURL=" . $server_result_url;
  $hash_string .= "&PaymentFormDisplaysResult=" . "false";
  $hash_string .= "&ServerResultURLCookieVariables=" . "";
  $hash_string .= "&ServerResultURLFormVariables=" . "";
  $hash_string .= "&ServerResultURLQueryStringVariables=" . "";
  $hash_digest = sha1($hash_string);
  break;
case "thankyou":
  $locationThankyou = $web_address . "/donate/thankyou";
  $locationError = $web_address . "/donate/payment";

  $accountNumber = null;
  $accountName = null;
  $sortCode = null;

  $_SESSION["hasError"] = false;
  $_SESSION["error"] = "";

  if (isset($_GET["HashDigest"])) {

    $_SESSION["CrossReference"] = $_GET["CrossReference"];
    $_SESSION["paymentMethod"] = "Credit Card";
    $_SESSION["CreateHash"] = create_hash($pre_shared_key, $password);
    $_SESSION["HashDigest"] = $_GET["HashDigest"];
    $_SESSION["MerchantID"] = $_GET["MerchantID"];
    $_SESSION["CheckHash"] = check_hash($pre_shared_key, $password);
    $_SESSION["OrderID"] = $_GET["OrderID"];

    $order_id = $_GET['OrderID'];

    $result_sql = "SELECT * FROM worldpay_status WHERE order_id = \"\{$order_id\}\"";
    $result = $wpdb->get_row($result_sql);

    $_SESSION["delete"] = $wpdb->delete('worldpay_status', array('order_id' => '{' . $order_id . '}'));

    if (false == $_SESSION["hasError"] && "5" == $result->status) {
      $_SESSION["error"] = $result->message;
      $_SESSION["hasError"] = true;
    }

    if (false == $_SESSION["hasError"] && "HASH PASSED" != $_SESSION["CheckHash"]) {
      hash_error_email();
    }
  }

  if (isset($_POST["accountName"])) {

    $_SESSION["paymentMethod"] = "Direct Debit";
    $accountNumber = $_POST["accountNumber"];
    $accountName = $_POST["accountName"];
    $sortCode = $_POST["sortCode"];
  }

  if (isset($_GET["paymentMethod"]) && $_GET["paymentMethod"] == "pay_pal") {
    $_SESSION["paymentMethod"] = "Pay Pal";
  }

  if (
    (
      isset($_GET["HashDigest"]) ||
      isset($_POST["accountName"]) ||
      isset($_GET["paymentMethod"])
    ) &&
    $_SESSION["hasError"] == true
  ) {
    wp_redirect($locationError);
    exit;
  }

  if (
    (
      isset($_GET["HashDigest"]) ||
      isset($_POST["accountName"]) ||
      isset($_GET["paymentMethod"])
    ) &&
    $_SESSION["hasError"] == false
  ) {
    export_to_saleforce($to_address, $accountName, $sortCode, $accountNumber);
    wp_redirect($locationThankyou);
    exit;
  }

  break;
}

if (SESSION_DEBUG == true) {
  echo ('var_dump($result->status != "0");');
  echo ("<pre>");
  var_dump($result->status != "0");
  echo ("</pre>");

  echo ('var_dump($result->status);');
  echo ("<pre>");
  var_dump($result->status);
  echo ("</pre>");

  echo ('var_dump($result);');
  echo ("<pre>");
  var_dump($result);
  echo ("</pre>");

  echo ('var_dump($result_sql);');
  echo ("<pre>");
  var_dump($result_sql);
  echo ("</pre>");

  echo ('var_dump($_SESSION);');
  echo ("<pre>");
  var_dump($_SESSION);
  echo ("</pre>");
}

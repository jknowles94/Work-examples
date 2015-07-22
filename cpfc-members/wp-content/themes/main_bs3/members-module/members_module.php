<?php
$path = ABSPATH . 'vendor/google/apiclient/src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once(ABSPATH . 'vendor/google/apiclient/src/Google/Client.php');
require_once(ABSPATH . 'vendor/google/apiclient/src/Google/Service/YouTube.php');
require_once(ABSPATH . 'vendor/vimeo/vimeo-api/autoload.php');

require_once(CPFC_MEMBERS_PATH . 'config.php');
require_once(CPFC_MEMBERS_PATH . 'classes/auth.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/email.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/form.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/input.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/messages.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/membership_reporting.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/model.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/notification.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/remote_image_upload.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/rss_feed.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/session.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/template_data.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/youtube.class.php');
require_once(CPFC_MEMBERS_PATH . 'classes/vimeo.class.php');

require_once(CPFC_MEMBERS_PATH . 'forms/forgotten_password.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/login.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/registration.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/password_reset.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/update_details.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/verification_email_request.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/data_capture/address.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/data_capture/contact.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/data_capture/preferences.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/data_capture/see_tickets.form.php');
require_once(CPFC_MEMBERS_PATH . 'forms/reporting/filter.form.php');
require_once(CPFC_MEMBERS_PATH . 'models/user.model.php');
require_once(CPFC_MEMBERS_PATH . 'models/member_notification.model.php');
require_once(CPFC_MEMBERS_PATH . 'models/membership_level.model.php');
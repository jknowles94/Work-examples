<?php
// Setup Bootstrap code
require_once('bootstrap.class.php');

// Get the new class to create post types
require_once('posttype.class.php');

// CPFC Members Module
require_once(CPFC_MEMBERS_PATH . 'members_module.php');

use \CPFCMembers\Input as Input;
use \CPFCMembers\Session as Session;

$core = new MainBS3Core();

class MainBS3Core
{
    protected $themePath, $adminCss = '', $siteJs = '';

    private $placeholderImages = array(
        'list' => '/wp-content/themes/main_bs3/images/placeholders/article-list.jpg',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set Plugin Path
        $this->themePath = dirname(__FILE__);

        add_action('init', array(&$this, 'init'));

        //Register Menus
        add_action('init', array(&$this, 'registerMenus'));

        // Add Custom Image Sizes
        add_action( 'init', array( &$this, 'addImageSizes') );

        // Add extra Admin pages
        add_action('admin_menu', array(&$this, 'addAdminPages'));

        // Load Admin CSS
        add_action('admin_head', array($this,'adminHead'));

        // Add Crons
        add_action('wp', array(&$this, 'setupCronSchedule'));
        add_action('youtube_cron', array(&$this, 'youTubeCron'));
        add_action('rss_feed_cron', array(&$this, 'rssCron'));

        // Add Custom Cron Schedules
        add_filter('cron_schedules', array(&$this, 'customCronSchedules'));

        // Hide "Members" (Registered E@gles Members) from appearing in WordPress' standard admin section
        //add_action('pre_user_query', array(&$this, 'hideMembers'));

        // "Load More" for YouTube Feed
        add_action("wp_ajax_youtube_load_more", array(&$this, "youtubeFeedMore"));
        add_action("wp_ajax_nopriv_youtube_load_more", array(&$this, "youtubeFeedMore"));

        // "Load More" for RSS Feed
        add_action("wp_ajax_rss_load_more", array(&$this, "rssFeedMore"));
        add_action("wp_ajax_nopriv_rss_load_more", array(&$this, "rssFeedMore"));

        // Hide/Rearrange Menu Items in Admin
        add_action('admin_menu', array(&$this, 'rearrangeMenuItems'));

         // Limit failed logins
        add_action('wp_authenticate',array('\CPFCMembers\Auth','loginAttemptLimiter'));
        add_action('login_errors',array('\CPFCMembers\Auth','loginAttemptError'), 100);
        add_filter('limit_login_whitelist_ip', array(&$this, 'limitLoginAttemptsDisable'), 10, 2);

        // Fetch Vimeo Video info after they're saved
        add_action('acf/save_post', array(&$this, 'vimeoVideoCache'), 20);

        // Added Options Pages
        $this->addOptionsPages();

        // Add New Roles
        $this->addRoles();

        // Create Post Types
        $this->_createPostTypes();

        // Membership Reporting
        add_action('init', array(\CPFCMembers\MembershipReporting::getInstance(), 'init'));
        add_action('admin_init', array(\CPFCMembers\MembershipReporting::getInstance(), 'adminInit'));

        // Delete member details on user deletion
        add_action('deleted_user', array(&$this, 'deleteMemberDetails'));

        session_start();
    }

    /**
     * Create all required Post Types
     * @return NULL
     */
    protected function _createPostTypes()
    {
        $postTypes = array(
            'news' => array(
                'plural'    => 'News',
                'singular'  => 'News Item',
                'args'      => array(
                    'taxonomies' => array('post_tag'),
                ),
                'menu_icon' => 497,
            ),
        );

        foreach ($postTypes as $postTypeId => $postType) {
            new postType($postTypeId, $postType['plural'], $postType['singular'], $postType['args']);

            if(isset($postType['menu_icon']))
                $this->adminCss .= "#adminmenu #menu-posts-".$postTypeId." div.wp-menu-image:before {content: '\\f".$postType['menu_icon']."';}";
        }
    }

    /**
     * Initialise Class
     * @return NULL
     */
    public function init()
    {
        if (!is_admin()) {
            $this->routing();

            // Mark a Member's Last Active date/time if they are logged in
            if (\CPFCMembers\Auth::isLoggedIn()) {
                \CPFCMembers\Auth::getUser()->setLastActive(date('Y-m-d H:i:s'))->save(true);
            }
        }

        // Redirect User from Admin if they are logged in as a Member
        if (is_admin() && \CPFCMembers\Auth::isLoggedIn() && !(defined('DOING_AJAX') && DOING_AJAX)) {
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            die();
        }

        // Hide WP Admin bar on front of site
        add_filter( 'show_admin_bar', '__return_false' );
    }

    /**
     * Add ACF5 Options Pages
     */
    public function addOptionsPages()
    {
        if( function_exists('acf_add_options_page') ) {
            // Add Global Options Page
            acf_add_options_page(array(
                'page_title'    => 'Global Options',
                'menu_title'    => 'Options',
                'menu_slug'     => 'acf-options',
                'capability'    => 'edit_posts',
            ));
        }
    }

    /**
     * Add Admin Pages
     */
    public function addAdminPages()
    {
        add_menu_page('YouTube API Authentication', 'YouTube API Auth', 'manage_options', 'youtube-api-auth', array($this, 'youTubeAuthPage'), 'dashicons-video-alt2');

        // Membership Reporting
        $membershipReporting = \CPFCMembers\MembershipReporting::getInstance();
        add_menu_page('Membership Reporting', 'Reporting', 'manage_options', 'membership-reporting', array($membershipReporting, 'displayPage'), 'dashicons-chart-area');
    }

    /**
     * Add Custom Image sizes
     */
    public function addImageSizes()
    {
        add_image_size('detail', 800, 600, 'auto');
    }

    /**
     * All navigation menus should be registered here
     */
    public function registerMenus()
    {
        register_nav_menus(array(
            'header'   => __( 'Header Menu' ),
            'external'   => __( 'External Menu' ),
            'footer'   => __( 'Footer Menu' )
        ));
    }

    /**
     * Remove unused menu items
     */
    public function rearrangeMenuItems()
    {
        global $menu;
        // Remove Posts
        remove_menu_page('edit.php');

        // Remove Comments
        remove_menu_page('edit-comments.php');

        // Move pages Menu to top
        $menu[8] = $menu[20];
        unset($menu[20]);

        // Move Blog Items Menu below Pages
        $menu[9] = $menu[26];
        unset($menu[26]);
    }

    /**
     * Routing method
     * @return NULL
     */
    public function routing()
    {
        $uri = trim(Input::uri(false), '/');
        $loginRedirect = false;

        switch (true) {
            case CPFC_MEMBERS_REGISTER_URL == $uri:
                $this->_register();
                break;
            case CPFC_MEMBERS_UPDATE_DETAILS_URL == $uri:
            	$loginRedirect = true;
                $this->_updateDetails();
                break;
            case CPFC_MEMBERS_MEMBERSHIPS_URL == $uri:
            	$loginRedirect = true;
            	$this->_memberships();
            	break;
            case CPFC_MEMBERS_LOGIN_URL == $uri:
                $this->_login();
                break;
            case CPFC_MEMBERS_LOGOUT_URL == $uri:
                $this->_logout();
                break;
            case CPFC_MEMBERS_REGISTRATION_VERIFICATION_URL == $uri:
                $this->_verifyRegistration();
                break;
            case CPFC_MEMBERS_FORGOTTEN_PASSWORD_URL == $uri:
                $this->_forgottenPassword();
                break;
            case CPFC_MEMBERS_PASSWORD_RESET_URL == $uri:
                $this->_passwordReset();
                break;
            case CPFC_MEMBERS_VERIFICATION_EMAIL_REQUEST_URL == $uri:
                $this->_verificationEmailRequest();
                break;
            case CPFC_MEMBERS_DATA_CAPTURE_URL == $uri:
                $this->_dataCapture();
                break;
            case CPFC_MEMBERS_YOUTUBE_AUTH_URL == $uri:
                $this->youTubeAuthCallback();
                break;
            case CPFC_MEMBERS_RSS_TEST_FETCH_URL == $uri:
                $this->rssCron();
                break;
            case CPFC_MEMBERS_VIEW_CAROUSEL_VIDEO_URL == $uri:
                $this->homepageVideoCarousel();
                break;

            default:
            	$loginRedirect = true;

        }

        if($loginRedirect){
        	\CPFCMembers\Session::set('login_redirect',Input::uri());
        }

    }

    /**
     * Register process
     */
    protected function _register()
    {
        // Can't access if already logged in as Member
        if (\CPFCMembers\Auth::isLoggedIn()) {
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }

        if (Input::isPost()) {
            $form = \CPFCMembers\RegistrationForm::getInstance();
            $form->setValues(Input::post());
            $form->validate();

            if ($form->isValid()) {
                $User = new \CPFCMembers\UserModel();
                $User->populateFromArray($form->getValues());
                $User->setRegistrationStatus('unverified');
                $User->setStatus('active');
                $User->setMembershipLevel('free');
                $User->setRegisteredDate(date('Y-m-d H:i:s'));
                $User->setSource('website');
                $User->generateAndStoreVerificationCode();
                if ($User->save(true)) {
                    $email = new \CPFCMembers\Email('email_templates/initial_registration', array('User'=>$User));
                    $email->send($User->getEmailAddress(), 'Your Account has been created');

                    // Log User in automatically
                    $Auth = \CPFCMembers\Auth::getInstance();
                    $Auth->authenticate($User->getEmailAddress(), $User->getPassword(), $remember);

					if($loginRedirect = \CPFCMembers\Session::get('login_redirect')){
						\CPFCMembers\Session::delete('login_redirect');
					}
					else{
						$loginRedirect = cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE) . '?registered';
					}

                    if ($loginRedirect == '' || $loginRedirect == '/') {
                        $loginRedirect .= '?registered';
                    }

					wp_redirect($loginRedirect, 301);
                    exit();
                } else {
                    $form->addError('form', 'Your registration could not be completed at this time');
                }
            }
        }
    }

    /**
     * Memberships process
     */
    protected function _memberships()
    {
        // Can't access if not logged in as Member
        if (!\CPFCMembers\Auth::isLoggedIn()) {
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }
    }

    /**
     * Update Details process
     */
    protected function _updateDetails()
    {
        // Can't access if not logged in as Member
        if (!\CPFCMembers\Auth::isLoggedIn()) {
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }

        $User = \CPFCMembers\Auth::getUser();
        $updateDetailsForm = \CPFCMembers\UpdateDetailsForm::getInstance();

        if (Input::isPost()) {
            // Request sent to Update User's Details
            if (Input::post('my_details_submit')) {
                $updateDetailsForm->setValues(Input::post());
                $updateDetailsForm->validate();

                if ($updateDetailsForm->isValid()) {
                    $User->populateFromArray($updateDetailsForm->getValues(), 'update_details_form');
                    $User->setLastUpdated(date('Y-m-d H:i:s'));

                    $newAccountNumber = $updateDetailsForm->getValue('see_tickets_account_number');
                    if ($updateDetailsForm->getValue('see_tickets_account_number') && !is_null(\CPFCMembers\Auth::getUser()->getSeeTicketsAccountNumber())) {
                        $User->setSeeTicketsAccountNumber($newAccountNumber);
                    }

                    if ($User->save(true)) {
                        \CPFCMembers\Messages::getInstance()->addSessionMessage('Your details have been updated', 'update_details');

                        wp_redirect(cpfc_home_url(CPFC_MEMBERS_UPDATE_DETAILS_URL, CPFC_MEMBERS_HTTP_MODE), 301);
                        exit();
                    } else {
                        $updateDetailsForm->addError('form', 'Your details could not be updated at this time');
                    }
                }
            } elseif (Input::post('password_reset_submit')) {
                $passwordResetForm = \CPFCMembers\PasswordResetForm::getInstance();
                $passwordResetForm->setValues(Input::post());
                $passwordResetForm->validate();

                if ($passwordResetForm->isValid()) {
                    $User->setPassword(Input::post('password'));

                    if ($User->updatePassword()) {
                        $User->setResetPasswordCode('');
                        $User->save(true);

                        $email = new \CPFCMembers\Email('email_templates/password_reset_notification', array('User'=>$User));
                        $email->send($User->getEmailAddress(), 'Your password has been reset');

                        \CPFCMembers\Messages::getInstance()->addSessionMessage('Your password has been updated', 'update_password');

                        wp_redirect(cpfc_home_url(CPFC_MEMBERS_UPDATE_DETAILS_URL, CPFC_MEMBERS_HTTP_MODE) . '#update-my-password', 301);
                        exit();
                    } else {
                        $passwordResetForm->addError('form', 'Your password could not be reset at this time');
                    }
                }
                $this->_setUpdateFormValues($updateDetailsForm,$User->toArray(false, false));
            }
        } else {
			$this->_setUpdateFormValues($updateDetailsForm,$User->toArray(false, false));
        }
    }

    private function _setUpdateFormValues($form,$values){
    	if(isset($values['email_address']))
    		unset($values['email_address']);

		$form->setValues($values);
    }

    /**
     * Login process
     */
    protected function _login()
    {
        // Can't access if already logged in as Member
        if (\CPFCMembers\Auth::isLoggedIn()) {
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }

		// Resend registration email for unverified members
		if(($email = Input::get('resend')) && ($member = \CPFCMembers\UserModel::loadBy('email_address',$email)) && $member->getRegistrationStatus() == 'unverified'){
            $form = \CPFCMembers\LoginForm::getInstance();
            $form->setValues(array('email_address' => $email));
			$form->addError('form','Thanks, we\'ve sent that to you now. Please check your email and click on the verification link, and then you\'ll be able to login.
<br /><br />
If you\'re still having problems, email us at: help@eagles.cpfc.co.uk');
			$email = new \CPFCMembers\Email('email_templates/verification_email_request', array('User'=>$member));
			$email->send($member->getEmailAddress(), 'Your Email Verification Request');
		}

        $Auth = \CPFCMembers\Auth::getInstance();

        if (Input::isPost()) {
            $form = \CPFCMembers\LoginForm::getInstance();
            $form->setValues(Input::post());
            $form->validate();

            if ($form->isValid()) {

				// Check for valid user
		        if(($user = get_user_by('email', $form->getValue('email_address'))) && wp_check_password($form->getValue('password'),$user->user_pass)){
					// Block unverified members after nth login attempt
	        		if(($member = \CPFCMembers\UserModel::loadBy('id',$user->ID)) && $member->getLoginCount() >= 3 && $member->getRegistrationStatus() == 'unverified'){
						$form->addError('form', 'It doesn\'t look like you\'ve confirmed your email address yet by clicking on the link in the email we sent you. To access the digital member\'s portal, please check your inbox and click on the verification link.
<br /><br />
If you would like us to send a new confirmation email, please <a href="?resend='.urlencode($form->getValue('email_address')).'">click here</a>.');
						return false;
		        	}
		        }

                if (!$Auth->authenticate($form->getValue('email_address'), $form->getValue('password'), $form->getValue('long_login') ? true : false)) {
                    $form->addError('form', 'Username and/or password incorrect.');
                    return false;
                }

				if($loginRedirect = \CPFCMembers\Session::get('login_redirect')){
					\CPFCMembers\Session::delete('login_redirect');
				}
				else{
					$loginRedirect = cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE);
				}

                wp_redirect($loginRedirect, 301);
                exit();
            }
        }
    }

    /**
     * Logout process and redirect
     */
    protected function _logout()
    {
        \CPFCMembers\Auth::logout();
        wp_redirect(cpfc_home_url(CPFC_MEMBERS_HOME_URL, CPFC_MEMBERS_HTTP_MODE), 301);
        exit();
    }

    /**
     * Verify a registration
     */
    protected function _verifyRegistration()
    {
        $templateData = \CPFCMembers\TemplateData::getInstance();

        // Code below won't work on WP-Engine due to cacheing
        /*$code = Input::get('code');
        // If a user with that verification code was found...
        if ($code) {
            Session::set('verificationCode', $code);
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_REGISTRATION_VERIFICATION_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }*/

        $status = 'INVALID_CODE';
        $code = Input::get('code');
        if ($code) {
            $User = \CPFCMembers\UserModel::loadByVerificationCode($code);

            if ($User->getId() && $User->getStatus() == 'active') {
                $User->setRegistrationStatus('verified');
                $User->setVerificationCode('');
                if ($User->save(true)) {
                    $status = 'SUCCESS';

                    $templateData->set('verifiedUser', $User);
                    //Session::delete('verificationCode');
                } else {
                    $status = 'USER_NOT_SAVED';
                }
            }
        }

        $templateData->set('verificationStatus', $status);
    }

    /**
     * Forgotten Password process and redirect
     */
    protected function _forgottenPassword()
    {
        if (Input::isPost()) {
            $form = \CPFCMembers\ForgottenPasswordForm::getInstance();
            $form->setValues(Input::post());
            $form->validate();

            if ($form->isValid()) {
                $User = \CPFCMembers\UserModel::loadByEmailAddress(Input::post('email_address'));
                $User->generateAndStoreResetPasswordCode();
                if ($User->save(true)) {
                    $email = new \CPFCMembers\Email('email_templates/forgotten_password', array('User'=>$User));
                    $email->send($User->getEmailAddress(), 'Your Password Reset Request');

                    //\CPFCMembers\Messages::getInstance()->addSessionMessage('A password reset request email has been sent to your inbox.', 'forgotten_password');

                    /*wp_redirect(cpfc_home_url(CPFC_MEMBERS_FORGOTTEN_PASSWORD_URL, CPFC_MEMBERS_HTTP_MODE), 301);
                    exit();*/
                    $form->setSuccess('A password reset request email has been sent to your inbox.');
                } else {
                    $form->addError('form', 'Your email address could not be found');
                }
            }
        }
    }

    /**
     * Password Reset process and redirect
     */
    protected function _passwordReset()
    {
        $templateData = \CPFCMembers\TemplateData::getInstance();
        // Code below won't work on WP-Engine due to cacheing
        /*$code = Input::get('code');
        if ($code) {
            Session::set('passwordResetCode', $code);
            wp_redirect(cpfc_home_url(CPFC_MEMBERS_PASSWORD_RESET_URL, CPFC_MEMBERS_HTTP_MODE), 301);
            exit();
        }
        $code = Session::get('passwordResetCode');*/

        $status = 'INVALID_CODE';
        $code = Input::request('code');
        if ($code) {
            $User = \CPFCMembers\UserModel::loadByResetPasswordCode($code);

            if ($User->getId()) {
                $status = 'USER_FOUND';
                if (Input::isPost()) {
                    $form = \CPFCMembers\PasswordResetForm::getInstance();
                    $form->setValues(Input::post());
                    $form->validate();

                    if ($form->isValid()) {
                        $User->setPassword(Input::post('password'));

                        if ($User->updatePassword()) {

                            $User->setResetPasswordCode('');
                            $User->save(true);

                            $email = new \CPFCMembers\Email('email_templates/password_reset_notification', array('User'=>$User));
                            $email->send($User->getEmailAddress(), 'Your Password has been Reset');

                            $status = 'SUCCESS';

                            //Session::delete('passwordResetCode');
                        } else {
                            $status = 'USER_NOT_SAVED';
                        }
                    }
                }
            }
        }

        $templateData->set('resetPasswordStatus', $status);
    }

    /**
     * Verification Email Request process and redirect
     */
    protected function _verificationEmailRequest()
    {
        $templateData = \CPFCMembers\TemplateData::getInstance();
        $form = \CPFCMembers\VerificationEmailRequestForm::getInstance();
        $status = '';
        if (Input::isPost()) {
            $form->setValues(Input::post());
            $form->validate();

            if ($form->isValid()) {
                $User = \CPFCMembers\UserModel::loadByEmailAddress(Input::post('email_address'));

                if ($User->getRegistrationStatus() == 'verified') {
                    $status = 'USER_ALREADY_VERIFIED';
                    $form->setValues(array('email_address'=>''));
                } else {
                    $User->generateAndStoreVerificationCode();
                    if ($User->save(true)) {
                        $email = new \CPFCMembers\Email('email_templates/verification_email_request', array('User'=>$User));
                        $email->send($User->getEmailAddress(), 'Verification Email Request email');

                        $status = 'SUCCESS';
                    } else {
                        $status = 'USER_NOT_SAVED';
                    }
                }
            }
        } else {
            $form->setValues(Input::get());
        }

        $templateData->set('verificationEmailRequest', $status);
    }

    /**
     * Data Capture
     */
    protected function _dataCapture()
    {
        $details = Input::request('details', false);
        $templateData = \CPFCMembers\TemplateData::getInstance();
        $User = \CPFCMembers\Auth::getUser();

        $template = '';
        $status = '';
        // Can't access if not logged in as Member
        if (\CPFCMembers\Auth::isLoggedIn()) {

            switch($details) {
                case 'address':
                    $form = \CPFCMembers\DataCaptureAddressForm::getInstance();
                    $template = 'partials/data_capture/address_form';
                    break;
                case 'contact':
                    $form = \CPFCMembers\DataCaptureContactForm::getInstance();
                    $template = 'partials/data_capture/contact_form';
                    break;
                case 'preferences':
                    $form = \CPFCMembers\DataCapturePreferencesForm::getInstance();
                    $template = 'partials/data_capture/preferences_form';
                    break;
                case 'see_tickets':
                    $form = \CPFCMembers\DataCaptureSeeTicketsForm::getInstance();
                    $template = 'partials/data_capture/see_tickets_form';
                    break;
                default:
                    $status = 'INVALID_DETAIL';
                    $template = 'partials/data_capture/error';
                    $templateData->set('error_type', 'invalid_form_chosen');
            }

            $notification = \CPFCMembers\MemberNotificationModel::loadByUserIdAndDetail($User->getId(), $details);

            if ($notification->getStatus() == 'complete') {
                $status = 'ALREADY_COMPLETED';
                $template = 'partials/data_capture/error';
                $templateData->set('error_type', 'already_completed');
            }

        } else {
            $status = 'NOT_LOGGED_IN';
            $template = 'partials/data_capture/error';
            $templateData->set('error_type', 'not_logged_in');
        }

        if ($status == '') {
            if (Input::isPost()) {
                $form->setValues(Input::post());
                $form->validate();

                if ($form->isValid()) {
                    switch($details) {
                        case 'address':
                            $User->setHouseNameNumber(Input::post('house_name_number'));
                            $User->setStreet(Input::post('street'));
                            $User->setTown(Input::post('town'));
                            $User->setCounty(Input::post('county'));
                            $User->setPostcode(Input::post('postcode'));
                            $User->setCountry(Input::post('country'));
                            break;
                        case 'contact':
                            $User->setMobilePhone(Input::post('mobile_phone'));
                            $User->setDaytimePhone(Input::post('daytime_phone'));
                            break;
                        case 'preferences':
                            $User->setPreferenceRegularNewsletter(Input::post('preference_regular_newsletter'));
                            $User->setPreferenceBreakingNews(Input::post('preference_breaking_news'));
                            $User->setPreferencePartners(Input::post('preference_partners'));
                            $User->setPreferenceSMS(Input::post('preference_sms'));
                            break;
                        case 'see_tickets':
                            $User->setSeeTicketsAccountNumber(Input::post('see_tickets_account_number'));
                            break;
                    }

                    if ($User->save(true)) {
                        $status = 'SUCCESS';

                        $notification->setUserId($User->getId())
                            ->setDetail($details)
                            ->setStatus('complete');
                        $notification->save(true);
                    } else {
                        $status = 'NOT_SAVED';
                        $template = 'partials/data_capture/error';
                    }
                }
            } else {
                $form->setValues($User->toArray(false, false));
            }
        }

        $templateData->set('template', $template);
        $templateData->set('status', $status);
        $templateData->set('message', $message);
    }

    /**
     * Add Member Role to WP
     */
    public function addRoles()
    {
        $memberRole = get_role('member');

        if (!$memberRole) {
            add_role('member', 'Member', array());
        }
    }

    /**
     * YouTube Auth page for the Admin
     */
    public function youTubeAuthPage()
    {
        $htmlBody = '<h1>YouTube API Authentication</h1>';

        $youTube = new \CPFCMembers\YouTube();
        switch (true) {
            case isset($_GET['clear_and_fetch_cache']):
                $youTube->clearCache();
                $this->youTubeCron();
                break;
            case isset($_GET['clear_all_settings']):
                $youTube->clearSettings();
                break;
            case isset($_GET['show_playlists']):
                $youTube = new \CPFCMembers\YouTube();
                $playlists = $youTube->fetchUserPlaylists();

                $htmlBody .= '
<h3>User\'s Playlists</h3>';

                if ($playlists) {
                    $htmlBody .= '
<ul>';
                    foreach ($playlists as $playlist) {
                        $htmlBody .= "<li>{$playlist['type']} ({$playlist['playlistId']})</li>";
                    }
                    $htmlBody .= '
</ul>';

                } else {
                    $htmlBody .= "<p>No related playlists could be found for this User.</p>";
                }

                break;
        }

        $youTube->checkAuth();

        $htmlBody .= '
<h3>Actions</h3>
<ul>
    <li><a href="' . home_url(CPFC_MEMBERS_YOUTUBE_AUTH_ADMIN_URL . '&clear_and_fetch_cache') . '" onClick="return confirm(\'Are you sure you want to clear and refresh the cache?\');">Clear & fetch cache</a></li>
    <li><a href="' . home_url(CPFC_MEMBERS_YOUTUBE_AUTH_ADMIN_URL . '&clear_all_settings') . '" onClick="return confirm(\'Are you sure you want to clear all settings, including the cache?\');">Clear all settings and cache</a></li>
    <li><a href="' . home_url(CPFC_MEMBERS_YOUTUBE_AUTH_ADMIN_URL . '&show_playlists') . '">Show related playlists</a></li>
</ul>';

        echo $htmlBody;
        die();
    }

    /**
     * YouTube Auth process (callback)
     */
    public function youTubeAuthCallback()
    {
        $youTube = new \CPFCMembers\YouTube();
        $youTube->authenticate();
        exit();
    }

    /**
     * Fetch and Store the thumbnails and info for the selected Vimeo videos
     * @param  int $postId    Post ID
     */
    public function vimeoVideoCache($postId)
    {
        if ($postId == 8) {
            $vimeo = new \CPFCMembers\Vimeo();
            $vimeo->fetchAndCache();
        }
    }

    /**
     * Custom cron durations
     * @param  array $schedules  Existing WP cron schedules
     * @return array
     */
    public function customCronSchedules($schedules)
    {
        // Adds once a minute to the existing schedules.
        $schedules['every_minute'] = array(
            'interval' => 60,
            'display' => __('Every Minute')
        );
        return $schedules;
    }

    /**
     * Set up crons on site when wp-cron.php
     */
    public function setupCronSchedule()
    {
        if (!wp_next_scheduled('youtube_cron')) {
            wp_schedule_event(time(), 'every_minute', 'youtube_cron');
        }

        if (!wp_next_scheduled('rss_feed_cron')) {
            wp_schedule_event(time(), 'every_minute', 'rss_feed_cron');
        }
    }

    /**
     * RSS Cron process
     */
    public function rssCron()
    {
        $feedUrl = get_field('rss_feed_url', 'option');
        $cacheCount = get_field('rss_feed_cache_count', 'option');

        if ($feedUrl) {
            $rss = new \CPFCMembers\Rss_Feed($feedUrl);
            $rss->fetchAndCache($cacheCount);
        }
    }

    /**
     * Load Video Carousel content
     * @return [type] [description]
     */
    public function homepageVideoCarousel()
    {
        global $wp_query;

        $videoId = Input::request('id', false);

        // Vimeo Videos for Carousel
        $vimeo = new \CPFCMembers\Vimeo();
        if ($videoId) {
            $video = $vimeo->fetchVideoInfoFromCache($videoId);

            if ($video) {
                get_partial('partials/homepage_carousel/vimeo_overlay', array('video'=>$video,'videoId'=>$videoId));
                exit;
            }
        }

        header("HTTP/1.0 404 Not Found - Archive Empty");
        $wp_query->set_404();
        require TEMPLATEPATH.'/404.php';
        exit;
    }

    /**
     * YouTube Cron process
     */
    public function youTubeCron()
    {
        $playlistId = get_field('youtube_feed_playlist_id', 'option');
        if ($playlistId) {
            $youTube = new \CPFCMembers\YouTube($playlistId);
            $youTube->fetchAndCache(CPFC_MEMBERS_YOUTUBE_CACHE_COUNT);
        }

        $academyPlaylistId = get_field('youtube_feed_academy_playlist_id', 'option');
        if ($academyPlaylistId) {
            $youTube = new \CPFCMembers\YouTube($academyPlaylistId);
            $youTube->fetchAndCache(CPFC_MEMBERS_YOUTUBE_CACHE_COUNT);
        }

        $popularPlaylistId = get_field('youtube_feed_popular_playlist_id', 'option');
        if ($popularPlaylistId) {
            $youTube = new \CPFCMembers\YouTube($popularPlaylistId);
            $youTube->fetchAndCache(CPFC_MEMBERS_YOUTUBE_CACHE_COUNT);
        }
    }

    /**
     * Load more functionality for YouTube Feed
     */
    public function youtubeFeedMore()
    {
        global $wp_query;

        $pageCount = (int) isset($_REQUEST['pageCount']) ? $_REQUEST['pageCount'] : 4;
        $pageIndex = (int) isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex'] : 1;
        $playlistId = (int) isset($_REQUEST['playlistId']) ? $_REQUEST['playlistId'] : false;
        $offset = $pageCount * $pageIndex;

        if ($playlistId) {
            $youTube = new \CPFCMembers\YouTube($playlistId);
            $videos = $youTube->fetchFromCache($pageCount, $offset);


            if($videos === false) {
                $result = false;
            } else {
                foreach ($videos as $index => $video) {
                    $video['duration'] = time_since_friendly($video['timestamp']);
                    $video['link'] = 'http://www.youtube.com/embed/' . $video['videoId'] . '?rel=0&amp;wmode=transparent';
                    //$video['localImage'] = cpfc_home_url($video['localImage']);
                    $videos[$index] = $video;
                }

                $result = $videos;
            }

            if(Input::isAjax()) {
                $result = json_encode($result);
                echo $result;
            } else {
                //Template name: Homepage (Logged Out) Template
                header("HTTP/1.0 404 Not Found - Archive Empty");
                $wp_query->set_404();
                require TEMPLATEPATH.'/404.php';
            }
        }

        die();
    }

    /**
     * Load more functionality for RSS Feed
     */
    public function rssFeedMore()
    {
        global $wp_query;

        $pageCount = (int) isset($_REQUEST['pageCount']) ? $_REQUEST['pageCount'] : 6;
        $pageIndex = (int) isset($_REQUEST['pageIndex']) ? $_REQUEST['pageIndex'] : 1;
        $offset = $pageCount * $pageIndex;

        $feedUrl = get_field('rss_feed_url', 'option');
        if ($feedUrl) {
            $rss = new \CPFCMembers\Rss_Feed($feedUrl);
            $rssFeedItems = $rss->fetchFromCache($pageCount, $offset);

            if($rssFeedItems === false) {
                $result = false;
            } else {
                $landingPage = get_page_by_path('news');
                $otherNewsCharacterCount = get_field('news_landing_other_news_news_content_preview_character_count', $landingPage->ID);
                $rssFeedPlaceholderImage = get_field('rss_feed_cache_placeholder_image', 'option');
                foreach ($rssFeedItems as $index => $rssFeedItem) {
                    if ($rssFeedItem['localImage']) {
                        $rssFeedImage = $rssFeedItem['localImage'];
                    } else {
                        $rssFeedImage = $rssFeedPlaceholderImage['url'];
                    }

                    $rssFeedItem['localImage'] = $rssFeedImage;
                    $rssFeedItem['duration'] = time_since_friendly($rssFeedItem['timestamp']);
                    $rssFeedItem['content'] = cpfc_truncate_copy($rssFeedItem['content'], $otherNewsCharacterCount);
                    $rssFeedItems[$index] = $rssFeedItem;
                }

                $result = $rssFeedItems;
            }

            if(Input::isAjax()) {
                $result = json_encode($result);
                echo $result;
            } else {
                //Template name: Homepage (Logged Out) Template
                header("HTTP/1.0 404 Not Found - Archive Empty");
                $wp_query->set_404();
                require TEMPLATEPATH.'/404.php';
            }
        }

        die();
    }

    /**
     * Hide "Members" (Registered E@gles Members) from appearing in WordPress' standard "Users" section
     * @param  WP_User_Query $user_search    User search object
     * @return NULL
     */
    public function hideMembers($user_search) {
        global $wpdb;
        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if ('users' == $screen->id) {
                $user_search->query_from .= " LEFT JOIN {$wpdb->usermeta} ON {$wpdb->users}.id = {$wpdb->usermeta}.user_id AND {$wpdb->usermeta}.meta_key = 'wp_user_level'";
                $user_search->query_where = str_replace('WHERE 1=1',
                  "WHERE 1=1 AND {$wpdb->usermeta}.meta_value>0",$user_search->query_where);
            }
        }
    }

    /**
     * Disable limit-login-attempts plugin (we use our own)
     * Basically tell it all IP addresses are white-listed
     * @param string $allow
     * @param string $ip
     * @return boolean
     */
    public function limitLoginAttemptsDisable($allow, $ip) {
        return true;
    }

    /**
     * Delete member details on user delete
     * @param int $user_id
     * @return NULL
     */
    public function deleteMemberDetails($user_id) {
    	$member = \CPFCMembers\UserModel::loadBy('id',$user_id);
    	if($member->getId()){
    		$member->setStatus('deleted');
    		$member->save();
    	}
    }

    /*
     * Output for Admin Header
     */
    public function adminHead()
    { ?>
<style type="text/css">
    <?php echo $this->adminCss; ?>
</style>
    <?php
    }

}
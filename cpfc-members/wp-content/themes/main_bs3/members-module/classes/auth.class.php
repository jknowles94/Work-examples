<?php
namespace CPFCMembers;

class Auth
{
    protected static $_instance = null;
    public $error;
    public static $login_error = false;
    public static $memberIdentifier = 'member';

    const   max_failed_logins = 5,
            login_lockout_period = 5; // minutes

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clearErrors();
    }

    /**
     * Get Instance of Auth Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            $className = get_called_class();
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * Authenticate User with passed credentials
     * @return boolean
     */
    public function authenticate($email, $password, $remember)
    {
        // Fetch User based on their email adddress
        $user = get_user_by('email', $email);

        // If user doesn't exist they can't be logged in
        if(!$user instanceof \WP_User) {
            return false;
        }

        // If user is not a member they can't be logged in
        if (!in_array(self::$memberIdentifier,$user->roles)) {
            return false;
        }

        $potentialUser = \CPFCMembers\UserModel::loadById($user->ID);

        if ($potentialUser->getStatus() != 'active') {
            return false;
        }

        $creds = array();
        $creds['user_login']    = $user->user_login;
        $creds['user_password'] = $password;
        $creds['remember']      = $remember;

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $this->error = $user->get_error_message();
            return false;
        }

        // Track login
        $potentialUser->trackSuccessfulLogin();

        return true;
    }

    /**
     * Destroy current logged-in Member's session
     * @return boolean
     */
    public static function logout()
    {
        global $current_user;

        if ($current_user->ID > 0) {
            if (in_array(self::$memberIdentifier,$current_user->roles)) {
                wp_logout();
                return true;
            }
        }

        return false;
    }

    /**
     * Empty Error Stack from all previous error messages
     * @return NULL
     */
    public function clearErrors()
    {
        $this->error = null;
    }

    /**
     * Return the User Model for the currently logged in User
     * @return boolean
     */
    public static function getUser()
    {
        global $current_user;

        return self::isLoggedIn() ? \CPFCMembers\UserModel::loadById($current_user->ID) : false;
    }

    /**
     * Is there a user currently logged in, and are they a member
     * @return boolean
     */
    public static function isLoggedIn()
    {
        global $current_user;

        if ($current_user->ID > 0) {
            if (in_array(self::$memberIdentifier,$current_user->roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Is there a user currently logged in, and are they an Administrator
     * @return boolean
     */
    public static function isAdmin()
    {
        global $current_user;

        if ($current_user->ID > 0) {
            if (in_array('administrator',$current_user->roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Limit number of incorrect logins
     */
    public static function loginAttemptLimiter($login) {

        # intercept admin login
        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if(isset($_POST['log']) && isset($_POST['pwd'])){
                $pwd = $_POST['pwd'];
            }
            else if(isset($_POST['user_login']) && isset($_POST['user_password'])){
                $pwd = $_POST['user_password'];
            }
            else return;

            $user = get_user_by('login', $login);

            if(is_object($user) && $user instanceof \WP_User){
                if(self::loginAttemptLockout($user)){
                    $login = null;
                    return;
                }

                # check if authentication will succeeed
                if(is_wp_error(wp_authenticate($login,$pwd))){
                    self::loginAttemptFailure($user);
                    self::loginAttemptLockout($user);
                }
                else{
                    self::loginAttemptSuccess($user);
                }

            }
        }

    }

    /**
     * Limit number of incorrect logins
     */
    public static function loginAttemptError($error) {
        return self::$login_error ? self::$login_error : $error;
    }

    /**
     * Check/enforce for login lockout
     * @param $user
     **/
    public static function loginAttemptLockout($user) {
        # is user locked out?
        if($user->get('failed_logins') >= self::max_failed_logins){
            if(!($lockedOut = $user->get('locked_out'))){
                update_user_meta($user->ID,'locked_out',$lockedOut = date('Y-m-d H:i:s'));
            }

            # locked out
            $lockMinutes = ceil(((strtotime($lockedOut) + 60*self::login_lockout_period) - time())/60);
            if($lockMinutes){

                self::$login_error = $lockMinutes == 1
                    ? 'Your account is locked for 1 minute due to too many incorrect password attempts'
                    : sprintf('Your account is locked for %d minutes due to too many incorrect password attempts', $lockMinutes);

                return true;
            }
            # release lockout
            else{
                update_user_meta($user->ID,'failed_logins','');
                update_user_meta($user->ID,'locked_out','');
            }
        }
        return false;
    }

    /**
     * Register login failure
     * @param $user
     **/
    public static function loginAttemptFailure($user) {
        $n = $user->get('failed_logins');
        update_user_meta($user->ID,'failed_logins',$n+1);
    }

    /**
     * Register login success for user
     * @param $user
     **/
    public static function loginAttemptSuccess($user) {
        update_user_meta($user->ID,'failed_logins','');
    }
}
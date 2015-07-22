<?php
namespace CPFCMembers;

class UserModel extends \CPFCMembers\Model
{
    protected $_id;
    protected $_title;
    protected $_first_name;
    protected $_last_name;
    protected $_dob;
    protected $_email_address;
    protected $_password;
    protected $_daytime_phone;
    protected $_mobile_phone;
    protected $_house_name_number;
    protected $_street;
    protected $_town;
    protected $_county;
    protected $_postcode;
    protected $_country;
    protected $_registration_status;
    protected $_status;
    protected $_membership_level;
    protected $_preference_regular_newsletter;
    protected $_preference_breaking_news;
    protected $_preference_partners;
    protected $_preference_sms;
    protected $_see_tickets_account_number;
    protected $_loyalty_points;
    protected $_verification_code;
    protected $_reset_password_code;
    protected $_last_active;
    protected $_last_login;
    protected $_last_updated;
    protected $_source;
    protected $_registered_date;
    protected $_created_date;
    protected $_modified_date;

    protected $_tableName = 'member_details';
    
    private $emailChanged = false;

    /**
     * Load user by a particular field
     * @param  string $field    The field to find by
     * @param  string $value    The field value
     * @return UserModel
     */
    public static function loadBy($field, $value)
    {
        global $wpdb;

        $UserModel = new UserModel();
        switch ($field) {
            case 'id':
                $memberData = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$UserModel->_tableName} WHERE user_id = %d AND status != 'deleted'", $value), ARRAY_A);
                break;
            case 'verification_code':
                $memberData = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$UserModel->_tableName} WHERE verification_code = %s AND status != 'deleted'", $value), ARRAY_A);
                break;
            case 'reset_password_code':
                $memberData = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$UserModel->_tableName} WHERE reset_password_code = %s AND status != 'deleted'", $value), ARRAY_A);
                break;
            case 'email_address':
                $memberData = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$UserModel->_tableName} WHERE email_address = %s AND status != 'deleted'", $value), ARRAY_A);
                break;
        }

        if ($memberData) {
            $UserModel->setId($memberData['user_id']);
            $UserModel->populateFromArray($memberData);

            if ($UserModel->getStatus() == 'deleted') {
                return new UserModel();
            }
        }

        return $UserModel;
    }

    /**
     * Load user by ID
     * @param  int $id    User ID
     * @return UserModel
     */
    public static function loadById($id)
    {
        return self::loadBy('id', $id);
    }

    /**
     * Load user by Verification Code
     * @param  string $code    Verification Code
     * @return UserModel
     */
    public static function loadByVerificationCode($code)
    {
        return self::loadBy('verification_code', $code);
    }

    /**
     * Load user by Reset Password Code
     * @param  string $code    Reset Password Code
     * @return UserModel
     */
    public static function loadByResetPasswordCode($code)
    {
        return self::loadBy('reset_password_code', $code);
    }

    /**
     * Load user by Email Address
     * @param  string $emailAddress    Email Address
     * @return UserModel
     */
    public static function loadByEmailAddress($emailAddress)
    {
        return self::loadBy('email_address', $emailAddress);
    }

    /**
     * Get the User's ID
     * @return mixed
     */
    public function getId()
    {
        return $this->__get('_id');
    }

    /**
     * Set the User's ID
     * @param mixed $value
     */
    public function setId($value)
    {
        return $this->__set('_id', $value);
    }

    /**
     * Get the User's Title
     * @return mixed
     */
    public function getTitle()
    {
        return $this->__get('_title');
    }

    /**
     * Set the User's Title
     * @param mixed $value
     */
    public function setTitle($value)
    {
        return $this->__set('_title', $value);
    }

    /**
     * Get the User's First Name
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->__get('_first_name');
    }

    /**
     * Set the User's First Name
     * @param mixed $value
     */
    public function setFirstName($value)
    {
        return $this->__set('_first_name', $value);
    }

    /**
     * Get the User's Last Name
     * @return mixed
     */
    public function getLastName()
    {
        return $this->__get('_last_name');
    }

    /**
     * Set the User's Last Name
     * @param mixed $value
     */
    public function setLastName($value)
    {
        return $this->__set('_last_name', $value);
    }

    /**
     * Get the User's Date of Birth
     * @return mixed
     */
    public function getDob($unit = 'all')
    {
        $value = $this->__get('_dob');

        if ($value) {
            switch ($unit) {
                case 'd':
                    return substr($value, -2);
                    break;
                case 'm':
                    return substr($value, 5, 2);
                    break;
                case 'y':
                    return substr($value, 0, 4);
                    break;
                case 'all':
                    $array = array();

                    $explodedDob = explode('-', $value);

                    list($array['y'], $array['m'], $array['d']) = $explodedDob;

                    return $array;
                    break;
            }
        }

        return $value;
    }

    /**
     * Set the User's Date of Birth
     * @param mixed $value
     */
    public function setDob($value)
    {
        if (is_array($value) && $value['y'] && $value['m'] && $value['d']) {
            return $this->__set('_dob', "{$value['y']}-{$value['m']}-{$value['d']}");
        }

        if (is_string($value)) {
            return $this->__set('_dob', $value);
        }

        return $this;
    }

    /**
     * Get the User's Email Address
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->__get('_email_address');
    }

    /**
     * Set the User's Email Address
     * @param mixed $value
     */
    public function setEmailAddress($value)
    {

		# if email has changed re-verification is required
		$current = $this->getEmailAddress();

		if($current !== null){
			if(($value != $current) && $this->getRegistrationStatus() == 'verified'){
				$this->setRegistrationStatus('unverified');
				$this->generateAndStoreVerificationCode();
			}
			$this->emailChanged = true;
		}
    
    
        return $this->__set('_email_address', $value);
    }

    /**
     * Get the User's Password
     * @return mixed
     */
    public function getPassword()
    {
        return $this->__get('_password');
    }

    /**
     * Set the User's Password
     * @param mixed $value
     */
    public function setPassword($value)
    {
        return $this->__set('_password', $value);
    }

    /**
     * Get the User's Daytime Phone
     * @return mixed
     */
    public function getDaytimePhone()
    {
        return $this->__get('_daytime_phone');
    }

    /**
     * Set the User's Daytime Phone
     * @param mixed $value
     */
    public function setDaytimePhone($value)
    {
        return $this->__set('_daytime_phone', $value);
    }

    /**
     * Get the User's Mobile Phone
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->__get('_mobile_phone');
    }

    /**
     * Set the User's Mobile Phone
     * @param mixed $value
     */
    public function setMobilePhone($value)
    {
        return $this->__set('_mobile_phone', $value);
    }

    /**
     * Get the User's Address 1
     * @return mixed
     */
    public function getHouseNameNumber()
    {
        return $this->__get('_house_name_number');
    }

    /**
     * Set the User's Address 1
     * @param mixed $value
     */
    public function setHouseNameNumber($value)
    {
        return $this->__set('_house_name_number', $value);
    }

    /**
     * Get the User's Address 2
     * @return mixed
     */
    public function getStreet()
    {
        return $this->__get('_street');
    }

    /**
     * Set the User's Address 2
     * @param mixed $value
     */
    public function setStreet($value)
    {
        return $this->__set('_street', $value);
    }

    /**
     * Get the User's Town
     * @return mixed
     */
    public function getTown()
    {
        return $this->__get('_town');
    }

    /**
     * Set the User's Town
     * @param mixed $value
     */
    public function setTown($value)
    {
        return $this->__set('_town', $value);
    }

    /**
     * Get the User's County
     * @return mixed
     */
    public function getCounty()
    {
        return $this->__get('_count');
    }

    /**
     * Set the User's County
     * @param mixed $value
     */
    public function setCounty($value)
    {
        return $this->__set('_county', $value);
    }

    /**
     * Get the User's Post Code
     * @return mixed
     */
    public function getPostcode()
    {
        return $this->__get('_postcode');
    }

    /**
     * Set the User's Post Code
     * @param mixed $value
     */
    public function setPostcode($value)
    {
        return $this->__set('_postcode', $value);
    }

    /**
     * Get the User's Country
     * @return mixed
     */
    public function getCountry()
    {
        return $this->__get('_country');
    }

    /**
     * Set the User's Country
     * @param mixed $value
     */
    public function setCountry($value)
    {
        return $this->__set('_country', $value);
    }

    /**
     * Get the User's Registration Status
     * @return mixed
     */
    public function getRegistrationStatus()
    {
        return $this->__get('_registration_status');
    }

    /**
     * Set the User's Registration Status
     * @param mixed $value
     */
    public function setRegistrationStatus($value)
    {
        return $this->__set('_registration_status', $value);
    }

    /**
     * Get the User's Status
     * @return mixed
     */
    public function getStatus()
    {
        return $this->__get('_status');
    }

    /**
     * Set the User's Status
     * @param mixed $value
     */
    public function setStatus($value)
    {
        return $this->__set('_status', $value);
    }

    /**
     * Get the User's Membership Level
     * @param  boolean $userFriendly Should the user-friendly version be returned
     * @return mixed
     */
    public function getMembershipLevel($userFriendly = false)
    {
        $value = $this->__get('_membership_level');
        if ($userFriendly) {
            $levels = \CPFCMembers\MembershipLevelModel::fetchAll();

            return isset($levels[$value]) ? $levels[$value] : 'Unknown';
        }

        return $value;
    }

    /**
     * Set the User's Membership Level
     * @param mixed $value
     */
    public function setMembershipLevel($value)
    {
        return $this->__set('_membership_level', $value);
    }

    /**
     * Get the User's Preference Regular Newsletter
     * @return mixed
     */
    public function getPreferenceRegularNewsletter()
    {
        return $this->__get('_preference_regular_newsletter');
    }

    /**
     * Set the User's Preference Regular Newsletter
     * @param mixed $value
     */
    public function setPreferenceRegularNewsletter($value)
    {
        return $this->__set('_preference_regular_newsletter', (string) $value);
    }

    /**
     * Get the User's Preference Breaking News
     * @return mixed
     */
    public function getPreferenceBreakingNews()
    {
        return $this->__get('_preference_breaking_news');
    }

    /**
     * Set the User's Preference Breaking News
     * @param mixed $value
     */
    public function setPreferenceBreakingNews($value)
    {
        return $this->__set('_preference_breaking_news', (string) $value);
    }

    /**
     * Get the User's Preference Partners
     * @return mixed
     */
    public function getPreferencePartners()
    {
        return $this->__get('_preference_partners');
    }

    /**
     * Set the User's Preference Partners
     * @param mixed $value
     */
    public function setPreferencePartners($value)
    {
        return $this->__set('_preference_partners', (string) $value);
    }

    /**
     * Set the User's Preference Partners
     * @param mixed $value
     */
    public function setPreferenceSMS($value)
    {
        return $this->__set('_preference_sms', (string) $value);
    }

    /**
     * Get the User's Preference Partners
     * @return mixed
     */
    public function getPreferenceSMS()
    {
        return $this->__get('_preference_sms');
    }

    /**
     * Get the User's SEE Tickets Account Number
     * @return mixed
     */
    public function getSeeTicketsAccountNumber()
    {
        return $this->__get('_see_tickets_account_number');
    }

    /**
     * Set the User's SEE Tickets Account Number
     * @param mixed $value
     */
    public function setSeeTicketsAccountNumber($value)
    {
        return $this->__set('_see_tickets_account_number', $value);
    }

    /**
     * Get the User's Loyalty Points
     * @return mixed
     */
    public function getLoyaltyPoints()
    {
        return $this->__get('_loyalty_points');
    }

    /**
     * Set the User's Loyalty Points
     * @param mixed $value
     */
    public function setLoyaltyPoints($value)
    {
        return $this->__set('_loyalty_points', $value);
    }

    /**
     * Get the User's Verification Code
     * @return mixed
     */
    public function getVerificationCode()
    {
        return $this->__get('_verification_code');
    }

    /**
     * Set the User's Verification Code
     * @param mixed $value
     */
    public function setVerificationCode($value)
    {
        return $this->__set('_verification_code', $value);
    }

    /**
     * Get the User's Reset Password Code
     * @return mixed
     */
    public function getResetPasswordCode()
    {
        return $this->__get('_reset_password_code');
    }

    /**
     * Set the User's Reset Password Code
     * @param mixed $value
     */
    public function setResetPasswordCode($value)
    {
        return $this->__set('_reset_password_code', $value);
    }

    /**
     * Get the User's Last Active
     * @return mixed
     */
    public function getLastActive()
    {
        return $this->__get('_last_active');
    }

    /**
     * Set the User's Last Active
     * @param mixed $value
     */
    public function setLastActive($value)
    {
        return $this->__set('_last_active', $value);
    }

    /**
     * Get the User's Last Login
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->__get('_last_login');
    }

    /**
     * Set the User's Last Login
     * @param mixed $value
     */
    public function setLastLogin($value)
    {
        return $this->__set('_last_login', $value);
    }

    /**
     * Get the User's Last Updated
     * @return mixed
     */
    public function getLastUpdated()
    {
        return $this->__get('_last_updated');
    }

    /**
     * Set the User's Last Updated
     * @param mixed $value
     */
    public function setLastUpdated($value)
    {
        return $this->__set('_last_updated', $value);
    }

    /**
     * Get the User's Source
     * @return mixed
     */
    public function getSource()
    {
        return $this->__get('_source');
    }

    /**
     * Set the User's Source
     * @param mixed $value
     */
    public function setSource($value)
    {
        return $this->__set('_source', $value);
    }

    /**
     * Get the User's Registered Date
     * @return mixed
     */
    public function getRegisteredDate()
    {
        return $this->__get('_registered_date');
    }

    /**
     * Set the User's Registered Date
     * @param mixed $value
     */
    public function setRegisteredDate($value)
    {
        return $this->__set('_registered_date', $value);
    }

    /**
     * Get the User's Created Date
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->__get('_created_date');
    }

    /**
     * Set the User's Created Date
     * @param mixed $value
     */
    public function setCreatedDate($value)
    {
        return $this->__set('_created_date', $value);
    }

    /**
     * Get the User's Modified Date
     * @return mixed
     */
    public function getModifiedDate()
    {
        return $this->__get('_modified_date');
    }

    /**
     * Set the User's Modified Date
     * @param mixed $value
     */
    public function setModifiedDate($value)
    {
        return $this->__set('_modified_date', $value);
    }

    /**
     * Set Model's properties from the passed array
     * @param  array $data  Array of Data
     * @return
     */
    public function populateFromArray($data, $source = 'db')
    {
        switch ($source) {
            case 'import':
                break;
            case 'update_details_form':
                $this->setTitle(isset($data['title']) ? $data['title'] : '')
                    ->setFirstName(isset($data['first_name']) ? $data['first_name'] : '')
                    ->setLastName(isset($data['last_name']) ? $data['last_name'] : '')
                    ->setDob(isset($data['dob']) ? $data['dob'] : '')
                    ->setDaytimePhone(isset($data['daytime_phone']) ? $data['daytime_phone'] : '')
                    ->setMobilePhone(isset($data['mobile_phone']) ? $data['mobile_phone'] : '')
                    ->setHouseNameNumber(isset($data['house_name_number']) ? $data['house_name_number'] : '')
                    ->setStreet(isset($data['street']) ? $data['street'] : '')
                    ->setTown(isset($data['town']) ? $data['town'] : '')
                    ->setCounty(isset($data['county']) ? $data['county'] : '')
                    ->setPostcode(isset($data['postcode']) ? $data['postcode'] : '')
                    ->setCountry(isset($data['country']) ? $data['country'] : '')
                    ->setPreferenceRegularNewsletter(isset($data['preference_regular_newsletter']) ? $data['preference_regular_newsletter'] : '0')
                    ->setPreferenceBreakingNews(isset($data['preference_breaking_news']) ? $data['preference_breaking_news'] : '0')
                    ->setPreferencePartners(isset($data['preference_partners']) ? $data['preference_partners'] : '0')
                    ->setPreferenceSMS(isset($data['preference_sms']) ? $data['preference_sms'] : '0');
                
                // only set email addre
                if(!empty($data['email_address']) && trim($data['email_address']))
					$this->setEmailAddress(isset($data['email_address']) ? $data['email_address'] : '');

                break;
            default:
                $this->setTitle(isset($data['title']) ? $data['title'] : '')
                    ->setFirstName(isset($data['first_name']) ? $data['first_name'] : '')
                    ->setLastName(isset($data['last_name']) ? $data['last_name'] : '')
                    ->setDob(isset($data['dob']) ? $data['dob'] : '')
                    ->setEmailAddress(isset($data['email_address']) ? $data['email_address'] : '')
                    ->setPassword(isset($data['password']) ? $data['password'] : '')
                    ->setDaytimePhone(isset($data['daytime_phone']) ? $data['daytime_phone'] : '')
                    ->setMobilePhone(isset($data['mobile_phone']) ? $data['mobile_phone'] : '')
                    ->setHouseNameNumber(isset($data['house_name_number']) ? $data['house_name_number'] : '')
                    ->setStreet(isset($data['street']) ? $data['street'] : '')
                    ->setTown(isset($data['town']) ? $data['town'] : '')
                    ->setCounty(isset($data['county']) ? $data['county'] : '')
                    ->setPostcode(isset($data['postcode']) ? $data['postcode'] : '')
                    ->setCountry(isset($data['country']) ? $data['country'] : '')
                    ->setRegistrationStatus(isset($data['registration_status']) ? $data['registration_status'] : '')
                    ->setStatus(isset($data['status']) ? $data['status'] : '')
                    ->setMembershipLevel(isset($data['membership_level']) ? $data['membership_level'] : '')
                    ->setPreferenceRegularNewsletter(isset($data['preference_regular_newsletter']) ? $data['preference_regular_newsletter'] : '0')
                    ->setPreferenceBreakingNews(isset($data['preference_breaking_news']) ? $data['preference_breaking_news'] : '0')
                    ->setPreferencePartners(isset($data['preference_partners']) ? $data['preference_partners'] : '0')
                    ->setPreferenceSMS(isset($data['preference_sms']) ? $data['preference_sms'] : '0')
                    ->setSeeTicketsAccountNumber(isset($data['see_tickets_account_number']) ? $data['see_tickets_account_number'] : '')
                    ->setLoyaltyPoints(isset($data['loyalty_points']) ? $data['loyalty_points'] : null)
                    ->setVerificationCode(isset($data['verification_code']) ? $data['verification_code'] : '')
                    ->setResetPasswordCode(isset($data['reset_password_code']) ? $data['reset_password_code'] : '')
                    ->setLastActive(isset($data['last_active']) ? $data['last_active'] : '')
                    ->setLastLogin(isset($data['last_login']) ? $data['last_login'] : '')
                    ->setLastUpdated(isset($data['last_updated']) ? $data['last_updated'] : '')
                    ->setSource(isset($data['source']) ? $data['source'] : '')
                    ->setRegisteredDate(isset($data['registered_date']) ? $data['registered_date'] : '')
                    ->setCreatedDate(isset($data['created_date']) ? $data['created_date'] : '')
                    ->setModifiedDate(isset($data['modified_date']) ? $data['modified_date'] : '');
        }



        return $this;
    }

    /**
     * Return array of User's data
     * @param  boolean $includeNull  Should Null Values be included in array?
     * @return array
     */
    public function toArray($includeNull = false, $dobAsString = true)
    {
        if ($includeNull) {
            $data = array(
                'id' => $this->getId(),
                'title' => $this->getTitle(),
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
                'dob' => $this->getDob($dobAsString ? false : 'all'),
                'email_address' => $this->getEmailAddress(),
                'daytime_phone' => $this->getDaytimePhone(),
                'mobile_phone' => $this->getMobilePhone(),
                'house_name_number' => $this->getHouseNameNumber(),
                'street' => $this->getStreet(),
                'town' => $this->getTown(),
                'county' => $this->getCounty(),
                'postcode' => $this->getPostcode(),
                'country' => $this->getCountry(),
                'registration_status' => $this->getRegistrationStatus(),
                'status' => $this->getStatus(),
                'membership_level' => $this->getMembershipLevel(),
                'preference_regular_newsletter' => $this->getPreferenceRegularNewsletter(),
                'preference_breaking_news' => $this->getPreferenceBreakingNews(),
                'preference_partners' => $this->getPreferencePartners(),
                'preference_sms' => $this->getPreferenceSMS(),
                'see_tickets_account_number' => $this->getSeeTicketsAccountNumber(),
                'loyalty_points' => $this->getLoyaltyPoints(),
                'verification_code' => $this->getVerificationCode(),
                'reset_password_code' => $this->getResetPasswordCode(),
                'last_active' => $this->getLastActive(),
                'last_login' => $this->getLastLogin(),
                'last_updated' => $this->getLastUpdated(),
                'source' => $this->getSource(),
                'registered_date' => $this->getRegisteredDate(),
                'created_date' => $this->getCreatedDate(),
                'modified_date' => $this->getModifiedDate(),
            );
        } else {
            $data = array();

            if ($this->getId()) {
                $data['id'] = $this->getId();
            }

            if ($this->getTitle()) {
                $data['title'] = $this->getTitle();
            }

            if ($this->getFirstName()) {
                $data['first_name'] = $this->getFirstName();
            }

            if ($this->getLastName()) {
                $data['last_name'] = $this->getLastName();
            }

            if ($this->getDob()) {
                $data['dob'] = $this->getDob($dobAsString ? false : 'all');
            }

            if ($this->getEmailAddress()) {
                $data['email_address'] = $this->getEmailAddress();
            }

            if ($this->getDaytimePhone()) {
                $data['daytime_phone'] = $this->getDaytimePhone();
            }

            if ($this->getMobilePhone()) {
                $data['mobile_phone'] = $this->getMobilePhone();
            }

            if ($this->getHouseNameNumber()) {
                $data['house_name_number'] = $this->getHouseNameNumber();
            }

            if ($this->getStreet()) {
                $data['street'] = $this->getStreet();
            }

            if ($this->getTown()) {
                $data['town'] = $this->getTown();
            }

            if ($this->getCounty()) {
                $data['county'] = $this->getCounty();
            }

            if ($this->getPostcode()) {
                $data['postcode'] = $this->getPostcode();
            }

            if ($this->getCountry()) {
                $data['country'] = $this->getCountry();
            }

            if ($this->getRegistrationStatus()) {
                $data['registration_status'] = $this->getRegistrationStatus();
            }

            if ($this->getStatus()) {
                $data['status'] = $this->getStatus();
            }

            if ($this->getMembershipLevel()) {
                $data['membership_level'] = $this->getMembershipLevel();
            }

            if (is_numeric($this->getPreferenceRegularNewsletter())) {
                $data['preference_regular_newsletter'] = $this->getPreferenceRegularNewsletter();
            }

            if (is_numeric($this->getPreferenceBreakingNews())) {
                $data['preference_breaking_news'] = $this->getPreferenceBreakingNews();
            }

            if (is_numeric($this->getPreferencePartners())) {
                $data['preference_partners'] = $this->getPreferencePartners();
            }

            if (is_numeric($this->getPreferenceSMS())) {
                $data['preference_sms'] = $this->getPreferenceSMS();
            }

            if ($this->getSeeTicketsAccountNumber()) {
                $data['see_tickets_account_number'] = $this->getSeeTicketsAccountNumber();
            }

            if (is_numeric($this->getLoyaltyPoints())) {
                $data['loyalty_points'] = $this->getLoyaltyPoints();
            }

            if ($this->getVerificationCode()) {
                $data['verification_code'] = $this->getVerificationCode();
            }

            if ($this->getResetPasswordCode()) {
                $data['reset_password_code'] = $this->getResetPasswordCode();
            }

            if ($this->getLastActive()) {
                $data['last_active'] = $this->getLastActive();
            }

            if ($this->getLastLogin()) {
                $data['last_login'] = $this->getLastLogin();
            }

            if ($this->getLastUpdated()) {
                $data['last_updated'] = $this->getLastUpdated();
            }

            if ($this->getSource()) {
                $data['source'] = $this->getSource();
            }

            if ($this->getRegisteredDate()) {
                $data['registered_date'] = $this->getRegisteredDate();
            }

            if ($this->getCreatedDate()) {
                $data['created_date'] = $this->getCreatedDate();
            }

            if ($this->getModifiedDate()) {
                $data['modified_date'] = $this->getModifiedDate();
            }
        }

        return $data;
    }

    /**
     * Save User
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return mixed
     */
    public function save($includeEmptyFields = false)
    {
        if ($this->getId()) {
            return $this->_update($includeEmptyFields);
        }

        return $this->_insert($includeEmptyFields);
    }

    /**
     * Insert User into DB
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return boolean       Did the insert succeed?
     */
    protected function _insert($includeEmptyFields)
    {
        global $wpdb;

        $standardData = array(
            'user_email' => $this->getEmailAddress(),
            'user_login' => $this->getEmailAddress() . '-' . md5(time()), // Used to randomise username as it is not used for frontend
            'user_pass'  => $this->getPassword(),
            'role'       => 'member',
        );

        $result = wp_insert_user($standardData);
        if(!$result instanceof WP_Error) {
            $userId = $result;

            $this->setCreatedDate(date('Y-m-d H:i:s'));
            $data = $this->toArray($includeEmptyFields);
            unset($data['id']);
            $format = array_fill(0, count($data), '%s');

            // Add UserID field
            $data['user_id'] = $userId;
            $format[] = '%d';

            $result = $this->_safeInsert($wpdb->prefix . $this->_tableName, $data, $format, true);

            return $result === false ? false : $this->setId($userId);
        }

        return $result;
    }

    /**
     * Update User in DB
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return boolean       Did the update succeed?
     */
    protected function _update($includeEmptyFields)
    {
        global $wpdb;

        $this->setModifiedDate(date('Y-m-d H:i:s'));
        $data = $this->toArray($includeEmptyFields);
        unset($data['id']);

        $format = array_fill(0, count($data), '%s');

        if($result = $this->_safeUpdate($wpdb->prefix . $this->_tableName, $data, $format, array('user_id' => $this->getId()), array('%d'), true)){
        	
        	if($this->emailChanged){
				$data = array(
					'ID'        => $this->getId(),
					'user_email' => $this->getEmailAddress(),
				);

				wp_update_user($data);

				$email = new \CPFCMembers\Email('email_templates/email_changed', array('User'=>$this));
				$email->send($this->getEmailAddress(), 'Verify Your New Email Address');

        	}
        	
        }
        
        return $result;
    }

    /**
     * Update User's Password in DB
     * @return boolean       Did the update succeed?
     */
    public function updatePassword()
    {
        $data = array(
            'ID'        => $this->getId(),
            'user_pass' => $this->getPassword(),
        );

        $result = wp_update_user($data);
        if(!$result instanceof WP_Error) {
            $this->setPassword('');
            return true;
        }

        return $result;
    }

    /**
     * Generate and store a new verification code
     * @return User object
     */
    public function generateAndStoreVerificationCode()
    {
        $code = sha1(microtime(true).mt_rand(10000,90000));

        return $this->setVerificationCode($code);
    }

    /**
     * Generate and store a new reset password code
     * @return User object
     */
    public function generateAndStoreResetPasswordCode()
    {
        $code = sha1(microtime(true).mt_rand(10000,90000));

        return $this->setResetPasswordCode($code);
    }

    /**
     * Track a successful login in the relevant table
     * @return return
     */
    public function trackSuccessfulLogin()
    {
        global $wpdb;

        $this->setLastLogin(date('Y-m-d H:i:s'))->save();

        $data = array(
            'user_id' => $this->getId(),
            'login_date' => date('Y-m-d H:i:s'),
        );

        $format = array(
            '%d',
            '%s',
        );

        return $wpdb->insert($wpdb->prefix . 'member_logins', $data, $format);
    }

    /**
     * Get number of times a User has logged-in
     * @return int
     */
    public function getLoginCount()
    {
        global $wpdb;

        $count = $wpdb->get_col($wpdb->prepare("SELECT COUNT(id) FROM {$wpdb->prefix}member_logins WHERE user_id = %d", $this->getId()));

        return $count ? reset($count) : 0;
    }

    /**
     * Generate dummy users
     * @param  integer $count Number of Users to create
     * @return false
     */
    public static function generateDummies($count = 100)
    {
        $characters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','Y','X','Z');

        $titles = \CPFCMembers\Form::getTitleOptions();
        $firstNames = array('Jake','Jon','Rohit','Victoria','Sophie','Thomas','Paul','Oliver','Stuart','Mel','Binal');
        $lastNames = array('Smith','Rogers','Jones','McDonald','Johnson-Thompson','Ellis','Ashley','O\'Flynn','Samuels','Barnard','Doe');
        $dob = array('start'=>mktime(0,0,0,1,1,1900),'finish'=>mktime(0,0,0,date("n"),date("j") ,date("Y")-13));
        $emailAddress = 'dummy-%s@test.com';
        $password = 'password';
        $daytimeNumber = array('02%d%d %d%d%d %d%d%d%d', '01%d%d%d %d%d%d%d%d%d');
        $mobileNumber = array('07%d%d%d %d%d%d%d%d%d', '');
        $houseNameNumbers = array('a','b','c','d','','','','','','','','','','','','');
        $streets = array('firstPart'=>array('Demo ','Test ','Layer ',''),'secondPart'=>array('Lane','Road','Street','Avenue','Crescent','Rd.','Av.','Close','St.'));
        $towns = array('Norwich','London','Bedford','Newquay','Nottingham','Edinburgh','Cardiff','Glasgow','Bangor','Reading','Stevenage','Swansea','Gloucester', '', '', '', '');
        $counties = array('Essex','London','Suffolk','Norfolk','Perthshire','Cornwall','Surrey','Kent','Yorkshire','Lancs','Gloucestershire','Herefordshire','Warwickshire', '', '', '', '');
        $postcode = array('%s%s%d %d%s%s', '');
        $countries = array_keys(\CPFCMembers\Form::getCountryOptions());
        $registrationStatuses = array('verified','unverified');
        $statuses = array('active','disabled','deleted');
        $membershipLevels = \CPFCMembers\MembershipLevelModel::fetchAll();
        $seeTicketsAccountNumber = array('%s%s%s-%d%d%d','','','','','');
        $loyaltyPoints = array('%d','0','0','','','');
        $source = 'website';
        $registeredDate = array('start'=>mktime(0,0,0,1,11,2014),'finish'=>time());

        $i = 0;
        while ($i < $count) {
            $User = new \CPFCMembers\UserModel();

            $User->setTitle($titles[array_rand($titles)]);
            $User->setFirstName($firstNames[array_rand($firstNames)]);
            $User->setLastName($lastNames[array_rand($lastNames)]);
            $User->setDob(date('Y-m-d',mt_rand($dob['start'],$dob['finish'])));
            $User->setEmailAddress(sprintf($emailAddress,sha1(mt_rand())));
            $User->setPassword($password);
            $User->setDaytimePhone(vsprintf($daytimeNumber[array_rand($daytimeNumber)], array(
                mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9))
            ));
            $User->setMobilePhone(vsprintf($mobileNumber[array_rand($mobileNumber)], array(
                mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9),mt_rand(0,9))
            ));
            $User->setHouseNameNumber(mt_rand(1,999).$houseNameNumbers[array_rand($houseNameNumbers)]);
            $User->setStreet(trim($streets['firstPart'][array_rand($streets['firstPart'])].$streets['secondPart'][array_rand($streets['secondPart'])]));
            $User->setTown($towns[array_rand($towns)]);
            $User->setCounty($counties[array_rand($counties)]);
            $User->setPostcode(vsprintf($postcode[array_rand($postcode)], array($characters[array_rand($characters)],$characters[array_rand($characters)],mt_rand(0,9),mt_rand(0,9),$characters[array_rand($characters)],$characters[array_rand($characters)])
            ));
            $User->setCountry($countries[array_rand($countries)]);
            $User->setRegistrationStatus($registrationStatuses[array_rand($registrationStatuses)]);
            $User->setStatus($statuses[array_rand($statuses)]);
            $User->setMembershipLevel(array_rand($membershipLevels));
            $User->setPreferenceRegularNewsletter(mt_rand(0,1));
            $User->setPreferenceBreakingNews(mt_rand(0,1));
            $User->setPreferencePartners(mt_rand(0,1));
            $User->setSeeTicketsAccountNumber(vsprintf($seeTicketsAccountNumber[array_rand($seeTicketsAccountNumber)],array($characters[array_rand($characters)],$characters[array_rand($characters)],$characters[array_rand($characters)],mt_rand(0,9),mt_rand(0,9),mt_rand(0,9))));
            $User->setLoyaltyPoints(sprintf($loyaltyPoints[array_rand($loyaltyPoints)],mt_rand(0,750)));
            $User->setSource($source);
            $User->setRegisteredDate(date('Y-m-d H:i:s',mt_rand($registeredDate['start'],$registeredDate['finish'])));

            $User->save(false);

            $i++;
        }
    }
}
<?php
namespace CPFCMembers;

class RegistrationForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'registration';
    protected $_securityMessage = 'For security reasons we could not submit your registration details. Please reload the page and fill out the form again.';
    protected $_requiredFields = array(
        'first_name',
        'last_name',
        'dob',
        'email_address',
        'confirm_email_address',
        'mobile_phone',
        'password',
        'confirm_password',
        'country',
        'terms_and_conditions',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\RegistrationForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateTitle();
        $this->validateFirstName();
        $this->validateLastName();
        $this->validateDob();
        $this->validateEmail();
        $this->validatePassword();

        $this->validateCountry();

        $this->validateMobilePhone();

        $this->validatePreferences();

        $this->validateTermsAndConditions();

        parent::validate();
    }

    /**
     * Validate the Title field
     */
    public function validateTitle()
    {
        $value = $this->getValue('title');
        if ($this->validateRequired('title')) {
            $this->addError('title', 'Please select your title');
        }
    }

    /**
     * Validate the First Name field
     */
    public function validateFirstName()
    {
        $value = $this->getValue('first_name');
        if ($this->validateRequired('first_name')) {
            $this->addError('first_name', 'Please enter your first name');
        }
    }

    /**
     * Validate the Last Name field
     */
    public function validateLastName()
    {
        $value = $this->getValue('last_name');
        if ($this->validateRequired('last_name')) {
            $this->addError('last_name', 'Please enter your last name');
        }
    }

    /**
     * Validate the Date of Birth field
     */
    public function validateDob()
    {
        $dayValue = $this->getValue('dob', 'd');
        $monthValue = $this->getValue('dob', 'm');
        $yearValue = $this->getValue('dob', 'y');
        $dobTimestamp = strtotime("{$yearValue}-{$monthValue}-{$dayValue}");
        $ageLimitTimestamp = mktime(0,0,0,date("n"), date("j"), date('Y') - 13); // Thirteen years ago today
        switch (true) {
            case $this->validateRequired('dob', 'd'):
            case $this->validateRequired('dob', 'm'):
            case $this->validateRequired('dob', 'y'):
            case ($dayValue || $monthValue || $yearValue) && !checkdate(intval($monthValue), intval($dayValue), intval($yearValue));
            case ($dayValue || $monthValue || $yearValue) && $dobTimestamp > time():
                $this->addError('dob', 'Please select a valid date of birth');
                break;
            case $dobTimestamp > $ageLimitTimestamp:
                $this->addError('dob', 'You must be at least 13 years of age to register');
                break;
        }
    }

    /**
     * Validate the Email Address field
     */
    public function validateEmail()
    {
        $value = $this->getValue('email_address');
        $existingUser = \CPFCMembers\UserModel::loadByEmailAddress($value);
        $existingAdminUser = get_user_by('email', $value);
        switch (true) {
            case $this->validateRequired('email_address'):
            case !filter_var($value, FILTER_VALIDATE_EMAIL):
                $this->addError('email_address', 'Please enter a valid email address');
                break;
            case $existingUser->getId():
            case $existingAdminUser instanceof \WP_User:
                $this->addError('email_address', 'An account with this email address already exists');
                break;
            case $value != $this->getValue('confirm_email_address'):
                $this->addError('email_address', 'Please make sure the email addresses match');
                break;
        }
    }

    /**
     * Validate the Password field
     */
    public function validatePassword()
    {
        $value = $this->getValue('password');
        switch (true) {
            case $this->validateRequired('password'):
            case is_null($value) || strlen($value) < 8:
                $this->addError('password', 'Please enter a password that\'s at least 8 characters in length');
                break;
            case $value != $this->getValue('confirm_password'):
                $this->addError('password', 'Please make sure your passwords match');
                break;
        }
    }

    /**
     * Validate the Country field
     */
    public function validateCountry()
    {
        $value = $this->getValue('country');
        if ($this->validateRequired('country')) {
            $this->addError('country', 'Please select a country');
        }
    }

    /**
     * Validate the Daytime Phone field
     */
    public function validateDaytimePhone()
    {
        $value = $this->getValue('daytime_phone');
        if ($this->validateRequired('daytime_phone')) {
            $this->addError('daytime_phone', 'Please enter a daytime phone number');
        }
    }

    /**
     * Validate the Mobile Phone field
     */
    public function validateMobilePhone()
    {
        $value = $this->getValue('mobile_phone');
        if ($this->validateRequired('mobile_phone')) {
            $this->addError('mobile_phone', 'Please enter a mobile phone number');
        }
    }

    /**
     * Validate the Terms and Conditions field
     */
    public function validateTermsAndConditions()
    {
        $value = $this->getValue('terms_and_conditions');
        if ($this->validateRequired('terms_and_conditions')) {
            $this->addError('terms_and_conditions', 'Please confirm that you accept our Terms and Conditions');
        }
    }

    /**
     * Validate the Preferences fields
     */
    public function validatePreferences() {}

}
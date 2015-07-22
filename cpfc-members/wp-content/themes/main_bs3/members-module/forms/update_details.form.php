<?php
namespace CPFCMembers;

class UpdateDetailsForm extends \CPFCMembers\RegistrationForm
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'update_details';
    protected $_securityMessage = 'For security reasons we could not update your details. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        'first_name',
        'last_name',
        'dob',
        'mobile_phone',
        'country',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\UpdateDetailsForm();
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

        $this->validateEmail();

        $this->validateDob();

        $this->validateMobilePhone();
        $this->validateDaytimePhone();

        $this->validateHouseNameNumber();
        $this->validateStreet();
        $this->validateTown();
        $this->validatePostCode();

        $this->validateCountry();

        $this->validateSeeTicketsAccountNumber();

        $this->validatePreferences();

        $this->validateTermsAndConditions();

        $this->validateSecurity();
    }

    /**
     * Validate the House Name/Number field
     */
    public function validateHouseNameNumber()
    {
        $value = $this->getValue('house_name_number');
        if ($this->validateRequired('house_name_number')) {
            $this->addError('house_name_number', 'Please enter the house name/number of your postal address');
        }
    }

    /**
     * Validate the Street field
     */
    public function validateStreet()
    {
        $value = $this->getValue('street');
        if ($this->validateRequired('street')) {
            $this->addError('street', 'Please enter the street in your postal address');
        }
    }

    /**
     * Validate the Town field
     */
    public function validateTown()
    {
        $value = $this->getValue('town');
        if ($this->validateRequired('town')) {
            $this->addError('town', 'Please enter the town in your postal address');
        }
    }

    /**
     * Validate the County field
     */
    public function validateCounty()
    {
        $value = $this->getValue('county');
        if ($this->validateRequired('county')) {
            $this->addError('county', 'Please enter the county in your postal address');
        }
    }

    /**
     * Validate the Post Code field
     */
    public function validatePostCode()
    {
        $value = $this->getValue('postcode');
        if ($this->validateRequired('postcode')) {
            $this->addError('postcode', 'Please enter a post code');
        }
    }

    /**
     * Validate the See Tickets Account Number field
     */
    public function validateSeeTicketsAccountNumber()
    {
        $value = $this->getValue('see_tickets_account_number');

        switch (true) {
            case $this->validateRequired('see_tickets_account_number'):
                $this->addError('see_tickets_account_number', 'Please enter your SEE Tickets Account Number');
                break;
            case $value && \CPFCMembers\Auth::getUser()->getSeeTicketsAccountNumber() != $value && is_null(\CPFCMembers\Auth::getUser()->getSeeTicketsAccountNumber()):
                $this->addError('see_tickets_account_number', 'You are not allowed to change your Account Number once it has been set');
                break;
        }


    }

    /**
     * Validate the Email Address field
     */
    public function validateEmail()
    {
        if($this->getValue('email_address'))
        	parent::validateEmail();
    }

}
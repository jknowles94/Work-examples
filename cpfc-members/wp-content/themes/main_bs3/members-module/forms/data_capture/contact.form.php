<?php
namespace CPFCMembers;

class DataCaptureContactForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'data_capture_contact';
    protected $_securityMessage = 'For security reasons we could not update your contact details. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        //'email_address',
        /*'daytime_phone',
        'mobile_phone',*/
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\DataCaptureContactForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateEmailAddress();
        $this->validateDaytimePhone();
        $this->validateMobilePhone();

        parent::validate();
    }

    /**
     * Validate the Email Address field
     */
    public function validateEmailAddress()
    {
        if ($this->validateRequired('email_address')) {
            $this->addError('email_address', 'Please enter your Email Address');
        }
    }

    /**
     * Validate the Daytime Phone field
     */
    public function validateDaytimePhone()
    {
        if ($this->validateRequired('daytime_phone')) {
            $this->addError('daytime_phone', 'Please enter your Daytime Phone Number');
        }
    }

    /**
     * Validate the Mobile Phone field
     */
    public function validateMobilePhone()
    {
        if ($this->validateRequired('mobile_phone')) {
            $this->addError('mobile_phone', 'Please enter your Mobile Phone Number');
        }
    }
}
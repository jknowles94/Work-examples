<?php
namespace CPFCMembers;

class VerificationEmailRequestForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'verification_email_request';
    protected $_securityMessage = 'For security reasons we could not verify your email address. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        'email_address',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\VerificationEmailRequestForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateEmail();

        parent::validate();
    }

    /**
     * Validate the Email Address field
     */
    public function validateEmail()
    {
        $value = $this->getValue('email_address');
        switch (true) {
            case $this->validateRequired('email_address'):
            case !filter_var($value, FILTER_VALIDATE_EMAIL):
                $this->addError('email_address', 'Please enter a valid email address');
                break;
        }
    }
}
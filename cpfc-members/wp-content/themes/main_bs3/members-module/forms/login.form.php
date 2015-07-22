<?php
namespace CPFCMembers;

class LoginForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'login';
    protected $_securityMessage = 'For security reasons we could not submit your login details. Please reload the page and re-submit the form.';

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\LoginForm();
        }
        return self::$_instance;
    }


    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateEmail();
        $this->validatePassword();

        parent::validate();
    }

    /**
     * Validate the Email Address field
     */
    public function validateEmail()
    {
        $value = $this->getValue('email_address');
        switch (true) {
            case is_null($value) || !strlen($value):
            case !filter_var($value, FILTER_VALIDATE_EMAIL):
                $this->addError('email_address', 'Please enter a valid email address');
                break;
        }
    }

    /**
     * Validate the Password field
     */
    public function validatePassword()
    {
        $value = $this->getValue('password');
        if (is_null($value) || !strlen($value)) {
            $this->addError('password', 'Please enter your password');
        }
    }
}
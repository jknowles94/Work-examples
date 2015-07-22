<?php
namespace CPFCMembers;

class PasswordResetForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'password_reset';
    protected $_securityMessage = 'For security reasons we could not set your new password. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        'password',
        'confirm_password',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\PasswordResetForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validatePassword();

        parent::validate();
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
}
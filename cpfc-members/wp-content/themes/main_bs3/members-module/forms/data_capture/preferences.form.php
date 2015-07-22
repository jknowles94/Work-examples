<?php
namespace CPFCMembers;

class DataCapturePreferencesForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'data_capture_preferences';
    protected $_securityMessage = 'For security reasons we could not update your contact preferences. Please reload the page and re-submit the form.';
    protected $_requiredFields = array();

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\DataCapturePreferencesForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        parent::validate();
    }
}
<?php
namespace CPFCMembers;

class DataCaptureAddressForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'data_capture_address';
    protected $_securityMessage = 'For security reasons we could not update your address details. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        /*'house_name_number',
        'street',
        'town',
        'county',
        'postcode',*/
        'country',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\DataCaptureAddressForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateHouseNameNumber();
        $this->validateStreet();
        $this->validateTown();
        $this->validateCounty();
        $this->validatePostCode();
        $this->validateCountry();

        parent::validate();
    }

    /**
     * Validate the House Name Number field
     */
    public function validateHouseNameNumber()
    {
        if ($this->validateRequired('house_name_number')) {
            $this->addError('house_name_number', 'Please enter your house name or number');
        }
    }

    /**
     * Validate the Street field
     */
    public function validateStreet()
    {
        if ($this->validateRequired('street')) {
            $this->addError('street', 'Please enter your street');
        }
    }

    /**
     * Validate the Town field
     */
    public function validateTown()
    {
        if ($this->validateRequired('town')) {
            $this->addError('town', 'Please enter your town');
        }
    }

    /**
     * Validate the County field
     */
    public function validateCounty()
    {
        if ($this->validateRequired('county')) {
            $this->addError('county', 'Please enter your county');
        }
    }

    /**
     * Validate the Post Code field
     */
    public function validatePostCode()
    {
        if ($this->validateRequired('postcode')) {
            $this->addError('postcode', 'Please enter a post code');
        }
    }

    /**
     * Validate the Country field
     */
    public function validateCountry()
    {
        if ($this->validateRequired('country')) {
            $this->addError('country', 'Please select a country');
        }
    }
}
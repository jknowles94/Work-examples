<?php
namespace CPFCMembers;

class DataCaptureSeeTicketsForm extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_nonceIdentifier = 'data_capture_see_tickets';
    protected $_securityMessage = 'For security reasons we could not update your SEE Tickets Account details. Please reload the page and re-submit the form.';
    protected $_requiredFields = array(
        'see_tickets_account_number',
    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\DataCaptureSeeTicketsForm();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateSeeTicketsAccountNumber();

        parent::validate();
    }

    /**
     * Validate the See Ticket Account Number field
     */
    public function validateSeeTicketsAccountNumber()
    {
        if ($this->validateRequired('see_tickets_account_number')) {
            $this->addError('see_tickets_account_number', 'Please enter your Account Number');
        }
    }
}
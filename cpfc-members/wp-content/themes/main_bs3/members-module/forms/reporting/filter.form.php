<?php
namespace CPFCMembers;

class ReportingFilter extends \CPFCMembers\Form
{
    protected static $_instance = null;

    protected $_requiredFields = array(

    );

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \CPFCMembers\ReportingFilter();
        }
        return self::$_instance;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        $this->validateRegistrationDate();
        $this->validateSeeTickets();
        $this->validateLoginDate();
    }

    /**
     * Validation Registration Data
     * @return NULL
     */
    public function validateRegistrationDate()
    {
        if ($this->getValue('cpfc_filter_member_submit')) {
            $registeredAfter = $this->getValue('registered_after');
            $registeredBefore = $this->getValue('registered_before');

            switch (true) {
                case $registeredAfter && $registeredBefore && strtotime($registeredAfter) > strtotime($registeredBefore):
                    $this->addError('registration_date', '"Registered After" cannot be later than "Registered Before"');
                    break;
            }

        }
    }

    /**
     * Validation See Tickets Data
     * @return NULL
     */
    public function validateSeeTickets()
    {
        if ($this->getValue('cpfc_filter_see_tickets_account_holder_submit')) {
            $accountNumber = $this->getValue('see_tickets_account_number');
            switch (true) {
                case strlen($accountNumber) == 0:
                    $this->addError('see_tickets_account_number', 'SEE Tickets Account Number cannot be empty');
                    break;
            }

        }
    }

    /**
     * Validation Login Data
     * @return NULL
     */
    public function validateLoginDate()
    {
        if ($this->getValue('cpfc_logged_in_filter_submit')) {
            $registeredAfter = $this->getValue('logged_in_after');
            $registeredBefore = $this->getValue('logged_in_before');

            switch (true) {
                case $registeredAfter && $registeredBefore && strtotime($registeredAfter) > strtotime($registeredBefore):
                    $this->addError('logged_in_date', '"Logged-in After" cannot be later than "Logged-in Before"');
                    break;
            }

        }
    }
}
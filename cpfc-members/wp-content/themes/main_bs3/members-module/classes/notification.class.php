<?php
/**
 * Notification Class
 */

namespace CPFCMembers;

class Notification
{
    protected static $_instance = null;
    protected $_thresholds = array();
    protected $_loginCount = 0;
    protected $_notification = null;

    /**
     * Get Instance of Members Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            $className = get_called_class();
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_loginCount = \CPFCMembers\Auth::getUser()->getLoginCount();
        $this->_thresholds = array(
            1 => 'registration_success',
            2 => 'see_tickets',
            3 => 'address',
            4 => 'contact',
            5 => 'preferences',
        );
    }

    /**
     * Should the User be shown a notification
     */
    public function notifyUser()
    {
        $detail = isset($this->_thresholds[$this->_loginCount]) ? $this->_thresholds[$this->_loginCount] : false;

        if ($detail) {
            $this->_notification = \CPFCMembers\MemberNotificationModel::loadByUserIdAndDetail(\CPFCMembers\Auth::getUser()->getId(), $detail);

            if ($this->_notification->getStatus() != 'complete') {
                return isset($this->_thresholds[$this->_loginCount]);
            }
        }

        return false;
    }

    /**
     * Should the User be shown a notification
     */
    public function displayNotification()
    {
        if ($this->notifyUser()) {
            $detail = $this->_thresholds[$this->_loginCount];

            $this->_notification->setUserId(\CPFCMembers\Auth::getUser()->getId())
                ->setDetail($detail)
                ->setStatus('incomplete');

            $this->_notification->save(true);

            get_partial('partials/notification_banner', array('detail' => $detail));
        }
    }
}
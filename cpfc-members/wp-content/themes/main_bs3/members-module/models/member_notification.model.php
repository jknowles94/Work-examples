<?php
namespace CPFCMembers;

class MemberNotificationModel extends \CPFCMembers\Model
{
    protected $_id;
    protected $_user_id;
    protected $_detail;
    protected $_status;
    protected $_created_date;
    protected $_modified_date;

    protected $_tableName = 'member_notifications';

    /**
     * Load user by a particular field
     * @param  string $field    The field to find by
     * @param  string $value    The field value
     * @return UserModel
     */
    public static function loadBy($field, $value)
    {
        global $wpdb;

        $MemberNotificationModel = new MemberNotificationModel();
        switch ($field) {
            case 'id':
                $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$MemberNotificationModel->_tableName} WHERE id = %d;", $value), ARRAY_A);
                break;
            case 'user_id_and_detail':
                $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}{$MemberNotificationModel->_tableName} WHERE user_id = %d AND detail = %s;", $value['user_id'], $value['detail']), ARRAY_A);
                break;
        }

        if ($data) {
            $MemberNotificationModel->populateFromArray($data);
        }

        return $MemberNotificationModel;
    }

    /**
     * Load user by ID
     * @param  int $id     ID
     * @return MemberNotificationModel
     */
    public static function loadById($id)
    {
        return self::loadBy('id', $id);
    }

    /**
     * Load user by User ID
     * @param  int $userId    User ID
     * @return MemberNotificationModel
     */
    public static function loadByUserId($userId)
    {
        return self::loadBy('user_id', $userId);
    }

    /**
     * Load user by User ID and Detail
     * @param  int $userId    User ID
     * @param  string $detail   Detail of Notification
     * @return MemberNotificationModel
     */
    public static function loadByUserIdAndDetail($userId, $detail)
    {
        return self::loadBy('user_id_and_detail', array('user_id'=>$userId,'detail'=>$detail));
    }

    /**
     * Get the ID
     * @return mixed
     */
    public function getId()
    {
        return $this->__get('_id');
    }

    /**
     * Set the ID
     * @param mixed $value
     */
    public function setId($value)
    {
        return $this->__set('_id', $value);
    }

    /**
     * Get the User ID
     * @return mixed
     */
    public function getUserId()
    {
        return $this->__get('_user_id');
    }

    /**
     * Set the User ID
     * @param mixed $value
     */
    public function setUserId($value)
    {
        return $this->__set('_user_id', $value);
    }

    /**
     * Get the Detail
     * @return mixed
     */
    public function getDetail()
    {
        return $this->__get('_detail');
    }

    /**
     * Set the Detail
     * @param mixed $value
     */
    public function setDetail($value)
    {
        return $this->__set('_detail', $value);
    }

    /**
     * Get the Status
     * @return mixed
     */
    public function getStatus()
    {
        return $this->__get('_status');
    }

    /**
     * Set the Status
     * @param mixed $value
     */
    public function setStatus($value)
    {
        return $this->__set('_status', $value);
    }

    /**
     * Get the User's Created Date
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->__get('_created_date');
    }

    /**
     * Set the User's Created Date
     * @param mixed $value
     */
    public function setCreatedDate($value)
    {
        return $this->__set('_created_date', $value);
    }

    /**
     * Get the User's Modified Date
     * @return mixed
     */
    public function getModifiedDate()
    {
        return $this->__get('_modified_date');
    }

    /**
     * Set the User's Modified Date
     * @param mixed $value
     */
    public function setModifiedDate($value)
    {
        return $this->__set('_modified_date', $value);
    }

    /**
     * Set Model's properties from the passed array
     * @param  array $data  Array of Data
     * @return
     */
    public function populateFromArray($data, $source = 'db')
    {
        switch ($source) {
            case 'db':
                $this->setId(isset($data['id']) ? $data['id'] : '');
            case 'form':
            $this->setUserId(isset($data['user_id']) ? $data['user_id'] : '')
                ->setDetail(isset($data['detail']) ? $data['detail'] : '')
                ->setStatus(isset($data['status']) ? $data['status'] : '')
                ->setCreatedDate(isset($data['created_date']) ? $data['created_date'] : '')
                ->setModifiedDate(isset($data['modified_date']) ? $data['modified_date'] : '');
        }

        return $this;
    }

    /**
     * Return array of User's data
     * @param  boolean $includeNull  Should Null Values be included in array?
     * @return array
     */
    public function toArray($includeNull = false, $dobAsString = true)
    {
        if ($includeNull) {
            $data = array(
                'id' => $this->getId(),
                'user_id' => $this->getUserId(),
                'detail' => $this->getDetail(),
                'status' => $this->getStatus(),
                'created_date' => $this->getCreatedDate(),
                'modified_date' => $this->getModifiedDate(),
            );
        } else {
            $data = array();

            if ($this->getId()) {
                $data['id'] = $this->getId();
            }

            if ($this->getUserId()) {
                $data['user_id'] = $this->getUserId();
            }

            if ($this->getDetail()) {
                $data['detail'] = $this->getDetail();
            }

            if ($this->getStatus()) {
                $data['status'] = $this->getStatus();
            }

            if ($this->getCreatedDate()) {
                $data['created_date'] = $this->getCreatedDate();
            }

            if ($this->getModifiedDate()) {
                $data['modified_date'] = $this->getModifiedDate();
            }
        }

        return $data;
    }

    /**
     * Save User
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return mixed
     */
    public function save($includeEmptyFields = false)
    {
        if ($this->getId()) {
            return $this->_update($includeEmptyFields);
        }

        return $this->_insert($includeEmptyFields);
    }

    /**
     * Insert User into DB
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return boolean       Did the insert succeed?
     */
    protected function _insert($includeEmptyFields)
    {
        global $wpdb;

        $this->setCreatedDate(date('Y-m-d H:i:s'));
        $data = $this->toArray($includeEmptyFields);
        $format = array_fill(0, count($data), '%s');

        return $this->_safeInsert($wpdb->prefix . $this->_tableName, $data, $format, true);
    }

    /**
     * Update User in DB
     * @param  boolean $includeEmptyFields  Should empty fields be included when saving?
     * @return boolean       Did the update succeed?
     */
    protected function _update($includeEmptyFields)
    {
        global $wpdb;

        $this->setModifiedDate(date('Y-m-d H:i:s'));
        $data = $this->toArray($includeEmptyFields);
        unset($data['id']);
        $format = array_fill(0, count($data), '%s');

        return $this->_safeUpdate($wpdb->prefix . $this->_tableName, $data, $format, array('id' => $this->getId()), array('%d'), true);
    }
}
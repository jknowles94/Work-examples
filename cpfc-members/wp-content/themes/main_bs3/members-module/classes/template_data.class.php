<?php
/**
 * Simple Template Data Class
 */

namespace CPFCMembers;

class TemplateData
{
    protected static $_instance = null;
    protected $_data;

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
        $this->_data = array();
    }

    /**
     * Set Template Data
     * @param string $index Index
     * @param mixed $value  Value
     */
    public function set($index, $value)
    {
        $this->_data[$index] = $value;

        return $this;
    }

    /**
     * Get Template Data
     * @param string $index Index
     * @return mixed
     */
    public function get($index)
    {
        return isset($this->_data[$index]) ? $this->_data[$index] : null;
    }
}
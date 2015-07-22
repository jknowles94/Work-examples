<?php
namespace CPFCMembers;

use \CPFCMembers\Session as Session;

/**
 * Basic Messages Class for
 */
class Messages {
    protected static $_instance = null;

    public $messages;
    public $messagesSessionName;
    public $types = array('success', 'error', 'notice');

    public function __construct()
    {
        $this->messagesSessionName = 'CPFC_Messages';

        $this->clearMessages();
        $sessionMessages = (array) Session::get($this->messagesSessionName);

        foreach ($this->types as $type) {
            if (isset($sessionMessages[$type])) {
                foreach ($sessionMessages[$type] as $message) {
                    $this->addMessage($message['copy'], $message['identifier'], $type);
                }
            }
        }

        Session::set($this->messagesSessionName, array());
    }

    /**
     * Get Instance of Messages Class (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            $className = get_class($this);
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * Clear Error Stack
     */
    public function clearMessages()
    {
        $this->messages = array();

        foreach ($this->types as $type) {
            $this->messages[$type] = array();
        }
    }

    /**
     * Add message to session message stack to appear on page reload
     * @param  string $copy         The text the message should include
     * @param  string $identifier   The specific item the message is related to
     * @param  string $type         Type of message ('success', 'error', 'notice')
     */
    public function addSessionMessage($copy, $identifier = '', $type = 'success')
    {
        $sessionMessages = (array) Session::get($this->messagesSessionName);
        if (!isset($sessionMessages[$type])) {
            $sessionMessages[$type] = array();
        }

        $sessionMessages[$type][] = array(
            'identifier' => $identifier,
            'copy'       => $copy,
        );

        Session::set($this->messagesSessionName, $sessionMessages);
    }

    /**
     * Add message to session message stack
     * @param  string $copy   The text the message should include
     * @param  string $identifier   The specific item the message is related to
     * @param  string $type   Type of message ('success', 'error', 'notice')
     */
    public function addMessage($copy, $identifier = '', $type = 'success')
    {
        $this->messages[$type][] = array(
            'identifier' => $identifier,
            'copy'       => $copy,
        );
    }

    /**
     * Display Messages stored in Message Stack
     * @param  string $type   Type of message ('success', 'error', 'notice')
     * @param  string $identifier   The specific item the message is related tos
     */
    public function displayMessages($type = 'success', $identifier = '')
    {
        foreach ($this->messages[$type] as $message) {
            if ($message['identifier'] == $identifier) { ?>
            <div class="message <?php echo $type; ?>"><?php echo $message['copy']; ?></div>
            <?php
            }
        }
    }
}
<?php
namespace CPFCMembers;

class Session
{
    /**
     * The get method allows you to read $_SESSION variables. Call without params to fetch the full array.
     * @param  mixed $index    The key in the $_SESSION array. Multi-dimensional arrays can be accessed by separating the levels by a dot
     * @param  mixed $default  What value should be returned if the array item is not found
     * @return mixed
     */
    public static function get($index = null, $default = null)
    {
        // Return all variables
        if (is_null($index))
            return $_SESSION;

        // If key is set return the value
        if (isset($_SESSION[$index]))
            return $_SESSION[$index];

        // Return default value
        return $default;
    }

    /**
     * Set a $_SESSION variable to the specified value
     * @param  mixed $index    The key in the $_SESSION array.
     * @param  mixed $value    What value the session variable should be set to
     */
    public static function set($index, $value)
    {
        $_SESSION[$index] = $value;
    }

    /**
     * Delete session variable
     * @param  mixed $index The key in the $_SESSION array.
     */
    public static function delete($index)
    {
        if (isset($_SESSION[$index])) {
            unset($_SESSION[$index]);
        }
    }
}
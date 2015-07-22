<?php
namespace CPFCMembers;

class Input
{
    /**
     * The get method allows you to read $_GET variables. Call without params to fetch the full array.
     * @param  string $index   The key in the $_GET array. Multi-dimensional arrays can be accessed by separating the levels by a dot
     * @param  mixed $default  What value should be returned if the array item is not found
     * @return mixed
     */
    public static function get($index = null, $default = null)
    {
        // Return all variables
        if (is_null($index))
            return array_map('stripslashes_deep', $_GET);

        // If key is set return the value
        if (isset($_GET[$index]))
            return stripslashes_deep($_GET[$index]);

        // Return default value
        return $default;
    }

    /**
     * The get method allows you to read $_POST variables. Call without params to fetch the full array.
     * @param  string $index   The key in the $_POST array. Multi-dimensional arrays can be accessed by separating the levels by a dot
     * @param  mixed $default  What value should be returned if the array item is not found
     * @return mixed
     */
    public static function post($index = null, $default = null)
    {
        // Return all variables
        if (is_null($index))
            return array_map('stripslashes_deep', $_POST);

        // If key is set return the value
        if (isset($_POST[$index]))
            return stripslashes_deep($_POST[$index]);

        // Return default value
        return $default;
    }

    /**
     * The get method allows you to read $_REQUEST variables. Call without params to fetch the full array.
     * @param  string $index   The key in the $_REQUEST array. Multi-dimensional arrays can be accessed by separating the levels by a dot
     * @param  mixed $default  What value should be returned if the array item is not found
     * @return mixed
     */
    public static function request($index = null, $default = null)
    {
        // Return all variables
        if (is_null($index))
            return array_map('stripslashes_deep', $_REQUEST);

        // If key is set return the value
        if (isset($_REQUEST[$index]))
            return stripslashes_deep($_REQUEST[$index]);

        // Return default value
        return $default;
    }

    /**
     * Return the URI
     * @return string
     */
    public static function uri($queryString = true)
    {
        if (!$queryString) {
            return strtok($_SERVER['REQUEST_URI'], '?');
        }

        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Is the HTTP method "POST"
     * @return boolean
     */
    public static function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * Is this AJAX
     * @return boolean
     */
    public static function isAjax()
    {
        return  ! empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) &&
      strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) == 'xmlhttprequest' ;
    }
}
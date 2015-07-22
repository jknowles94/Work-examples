<?php

if (!defined('CPFC_MEMBERS_PATH')) {
    define('CPFC_MEMBERS_PATH', rtrim(__DIR__, '/') . '/members-module/');
}

//Setup Theme Core
require_once('classes/core.class.php');

/**
 * Include the specified template
 * @param  string  $template Template filename without ".php" suffix
 * @param  array   $vars     Variables to be injected into template
 * @param  boolean $return   Should the template be returned or echoed
 * @return string/NULL       Contents of template with variables injected
 */
function get_partial($template,$vars=null,$return=false){
    if(is_array($vars)){
        foreach($vars as $k=>$v)
            $$k = $v;
    }
    if($file = locate_template($template.'.php')){
        if($return)ob_start();
        include($file);
        if($return)return ob_get_clean();

    }
    else
        throw new Exception('Template file not found: '.$template.'.php');
}

/**
 * Should the Cycle2 logs be show in the console?
 * @return string
 */
function show_cycle_2_logs(){
    if (defined('ENABLE_CYCLE2_LOGS') && ENABLE_CYCLE2_LOGS) {
        return 'true';
    }

    return 'false';
}

/**
 * Return a phrase for the time since the date
 * @param  int $timestamp  The Unix timestamp to use for the "since" date
 * @return string
 */
function time_since_friendly($timestamp)
{
    $now = time();
    $difference = $now - $timestamp;

    switch (true) {
        case $difference < 0:
            return ' Just now';
            break;
        case $difference < 60:
            $seconds = $difference;
            return $seconds . ' ' . (1 == $seconds ? 'second' : 'seconds') . ' ago';
            break;
        case $difference < 3600:
            $minutes = floor($difference / 60);
            return $minutes . ' ' . (1 == $minutes ? 'minute' : 'minutes') . ' ago';
            break;
        case $difference < 86400:
            $hours = floor($difference / 3600);
            return $hours . ' ' . (1 == $hours ? 'hour' : 'hours') . ' ago';
            break;
        case $difference < 604800:
            $days = floor($difference / 86400);
            return $days . ' ' . (1 == $days ? 'day' : 'days') . ' ago';
            break;
    }

    return 'Over a week ago';
}

/**
 * Our own home_url function (to always include a slash on the end of URLs)
 * @param  string $path  The path to use
 * @return string
 */
function cpfc_home_url($path = '')
{
    return trim(home_url($path), '/') . '/';
}

/**
 * Return a truncated version of the copy, if it's longer than the limit
 * @param  string $copy           Original copy
 * @param  int $characterLimit    Number of characters to show
 * @return string                 Truncated copy
 */
function cpfc_truncate_copy($copy, $characterLimit, $moreCopy = '...')
{
    $copy = strip_tags($copy);
    if (strlen($copy) > $characterLimit) {
        return substr($copy, 0, $characterLimit) . $moreCopy;
    }

    return $copy;
}
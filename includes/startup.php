<?php
/**
*
* @package reader
* @version $Id$
* @copyright Copyright (c) 2013, Firat Akandere
* @author Firat Akandere <f.akandere@gmail.com>
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License, version 3
*
*/

/**
* @ignore
*/
if (!defined('IN_MANGAREADER'))
{
    exit;
}

if (!defined('E_DEPRECATED'))
{
    define('E_DEPRECATED', 8192);
}
$level = E_ALL & ~E_NOTICE & ~E_DEPRECATED;
error_reporting($level);

/*
* Remove variables created by register_globals from the global scope
* Thanks to Matt Kavanagh
*/
function deregister_globals()
{
    $not_unset = array(
        'GLOBALS'	=> true,
        '_GET'		=> true,
        '_POST'		=> true,
        '_COOKIE'	=> true,
        '_REQUEST'	=> true,
        '_SERVER'	=> true,
        '_SESSION'	=> true,
	'_ENV'		=> true,
	'_FILES'	=> true,
	'mangareader_root_path'	=> true
    );

    // Not only will array_merge and array_keys give a warning if
    // a parameter is not an array, array_merge will actually fail.
    // So we check if _SESSION has been initialised.
    if (!isset($_SESSION) || !is_array($_SESSION))
    {
        $_SESSION = array();
    }

    // Merge all into one extremely huge array; unset this later
    $input = array_merge(
        array_keys($_GET),
        array_keys($_POST),
        array_keys($_COOKIE),
        array_keys($_SERVER),
        array_keys($_SESSION),
        array_keys($_ENV),
        array_keys($_FILES)
    );

    foreach ($input as $varname)
    {
        if (isset($not_unset[$varname]))
        {
            // Hacking attempt. No point in continuing unless it's a COOKIE (so a cookie called GLOBALS doesn't lock users out completely)
            if ($varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
            {
                exit;
            }
            else
            {
                $cookie = &$_COOKIE;
                while (isset($cookie['GLOBALS']))
                {
                    if (!is_array($cookie['GLOBALS']))
                    {
                        break;
                    }

                    foreach ($cookie['GLOBALS'] as $registered_var => $value)
                    {
                        if (!isset($not_unset[$registered_var]))
                        {
                            unset($GLOBALS[$registered_var]);
			}
		    }

                    $cookie = &$cookie['GLOBALS'];
		}
	    }
	}

	unset($GLOBALS[$varname]);
    }

    unset($input);
}

// Register globals and magic quotes have been dropped in PHP 5.4
if (version_compare(PHP_VERSION, '5.4.0-dev', '>='))
{
    /**
    * @ignore
    */
    define('STRIP', false);
}
else
{
    @set_magic_quotes_runtime(0);

    // Be paranoid with passed vars
    if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' || !function_exists('ini_get'))
    {
    	deregister_globals();
    }

    define('STRIP', (get_magic_quotes_gpc()) ? true : false);
}

// Prevent date/time functions from throwing E_WARNING on PHP 5.3 by setting a default timezone
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
    date_default_timezone_set(@date_default_timezone_get());
}

$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

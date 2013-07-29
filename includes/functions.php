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

/**
* set_var
*
* Set variable, used by {@link request_var the request_var function}
*
* @access private
*/
function set_var(&$result, $var, $type, $multibyte = false)
{
    settype($var, $type);
    $result = $var;

    if ($type == 'string')
    {
        $result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $result), ENT_COMPAT, 'UTF-8'));

        if (!empty($result))
        {
            // Make sure multibyte characters are wellformed
            if ($multibyte)
            {
                if (!preg_match('/^./u', $result))
                {
                    $result = '';
                }
            }
            else
            {
                // no multibyte, allow only ASCII (0-127)
                $result = preg_replace('/[\x80-\xFF]/', '?', $result);
            }
        }

	$result = (STRIP) ? stripslashes($result) : $result;
    }
}

/**
* request_var
*
* Used to get passed variable
*/
function request_var($var_name, $default, $multibyte = false, $cookie = false)
{
    if (!$cookie && isset($_COOKIE[$var_name]))
    {
        if (!isset($_GET[$var_name]) && !isset($_POST[$var_name]))
        {
        	return (is_array($default)) ? array() : $default;
		}
		$_REQUEST[$var_name] = isset($_POST[$var_name]) ? $_POST[$var_name] : $_GET[$var_name];
	}

	$super_global = ($cookie) ? '_COOKIE' : '_REQUEST';
	if (!isset($GLOBALS[$super_global][$var_name]) || is_array($GLOBALS[$super_global][$var_name]) != is_array($default))
	{
		return (is_array($default)) ? array() : $default;
	}

	$var = $GLOBALS[$super_global][$var_name];
	if (!is_array($default))
	{
		$type = gettype($default);
	}
	else
	{
		list($key_type, $type) = each($default);
		$type = gettype($type);
		$key_type = gettype($key_type);
		if ($type == 'array')
		{
			reset($default);
			$default = current($default);
			list($sub_key_type, $sub_type) = each($default);
			$sub_type = gettype($sub_type);
			$sub_type = ($sub_type == 'array') ? 'NULL' : $sub_type;
			$sub_key_type = gettype($sub_key_type);
		}
	}

	if (is_array($var))
	{
		$_var = $var;
		$var = array();

		foreach ($_var as $k => $v)
		{
			set_var($k, $k, $key_type);
			if ($type == 'array' && is_array($v))
			{
				foreach ($v as $_k => $_v)
				{
					if (is_array($_v))
					{
						$_v = null;
					}
					set_var($_k, $_k, $sub_key_type, $multibyte);
					set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
				}
			}
			else
			{
				if ($type == 'array' || is_array($v))
				{
					$v = null;
				}
				set_var($var[$k], $v, $type, $multibyte);
			}
		}
	}
	else
	{
		set_var($var, $var, $type, $multibyte);
	}

	return $var;
}

/**
* Set config, generate missing ones
*
* @param string $config_name Unique config name
* @param string $config_value Value for the config
*
* @return void
*/
function set_config($config_name, $config_value)
{
    global $config, $cache, $db;

    // If the config already exists and the value of it is still the same, do nothing
    if (isset($config[$config_name]) && $config[$config_name] == $config_value)
    {
        return;
    }

    $data_ary = array('config_value' => $config_value);

    $sql = 'UPDATE ' . CONFIG_TABLE . '
            SET ' . $db->build_array('UPDATE', $data_ary) . '
            WHERE config_name = ' . $db->quote($config_name);

    // If not any column is affected, which means the config is missing, generate it
    if (!$db->exec($sql))
    {
        $data_ary['config_name'] = $config_name;
        $sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' .$db->build_array('INSERT', $data_ary);
        $db->exec($sql);
    }

    $config[$config_name] = $config_value;
    $cache->remove_end('config');
}

/**
* Check if path is writable
*
* This will work for both *nix and windows systems
* @see https://bugs.php.net/bug.php?id=27609
* @see https://bugs.php.net/bug.php?id=30931
*
* @param string $path Use trailing slash for folders!
* @return boolean Either true if the path is writable or false if it is not
*/
function reader_is_writable($path)
{
    if (strtolower(substr(PHP_OS, 0, 3)) == 'win' || !function_exists('is_writable'))
    {
        if (file_exists($path))
        {
	    $path = realpath($path);

            if (is_dir($path))
            {
                // Try creating a new file in directory
                $result = @tempnam($path, 'i_w');

                if (is_string($result) && file_exists($result))
                {
                    unlink($result);
                    // Ensure the file is actually in the directory
		    return (strpos($result, $path) === 0) ? true : false;
                }
            }
            else
            {
		$handle = @fopen($path, 'r+');

		if (is_resource($handle))
		{
		    fclose($handle);
		    return true;
		}
	    }
        }
	else
	{
	    // file does not exist test if we can write to the directory
	    $dir = dirname($path);

	    if (file_exists($dir) && is_dir($dir) && reader_is_writable($dir))
	    {
		return true;
	    }
	}

	return false;
    }
    else
    {
        return is_writable($path);
    }
}

/**
* Generate fingerprint for user browser
* @todo Find a better way of getting fingerprint
* @return string hashed fingerprint
*/
function get_browser_fingerprint()
{
    global $config;
    $data = '';
    $data .= get_ip();
    $data .= (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $data .= (isset($_SERVER['HTTP_ACCEPT'])) ? $_SERVER['HTTP_ACCEPT'] : '';
    $data .= $config['board_salt'];
    $data .= (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
    $data .= (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

    return hash('sha256', $data);
}

/**
* Get user ip
* @todo IPv6 support
* @return string User IP
*/
function get_ip()
{
    $table = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    );

    foreach ($table as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (explode(',', $_SERVER[$key]) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

/**
* Generate page header
*/
function page_header()
{
    global $config;

    if ($config['gzip_compress'])
    {
        if (@extension_loaded('zlib') && !headers_sent() && ob_get_level() <= 1 && ob_get_length() == 0)
        {
            ob_start('ob_gzhandler');
        }
    }

    // application/xhtml+xml not used because of IE
    header('Content-type: text/html; charset=UTF-8');

    header('Cache-Control: private, no-cache="set-cookie"');
    header('Expires: 0');
    header('Pragma: no-cache');

    return;
}

/**
* Page footer
*/
function page_footer()
{
    if (defined('DEBUG'))
    {
	/**
	* @todo Make a better DB debugger view
	*/
	global $db;
	var_dump($db->errorInfo());
    }
    exit_handler();
}

function exit_handler()
{
    (ob_get_level() > 0) ? @ob_flush() : @flush();
    exit;
}

/**
* Generate url
*
* @param string $suffix Suffix that will be appended to the website domain
* @param string $mod_rewrite_suffix Permalink suffix
*
* @return string Generated url
*/
function generate_url($suffix, $mod_rewrite_suffix = false)
{
    global $config;

    // Make sure it does not start with trailing slash
    if (substr($suffix, 0, 1) == '/')
    {
	$suffix = substr($suffix, 1);
    }
    if ($mod_rewrite_suffix !== false && substr($mod_rewrite_suffix, 0, 1) == '/')
    {
	$mod_rewrite_suffix = substr($mod_rewrite_suffix, 1);
    }

    $url = '';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    {
	$url .= 'https://';
    }
    else
    {
	$url .= 'http://';
    }

    if ($mod_rewrite_suffix !== false && $config['mod_rewrite'] == 1)
    {
	$url .= $config['domain'] . '/' . $mod_rewrite_suffix;
    }
    else
    {
	$url .= $config['domain'] . '/' . $suffix;
    }

    return $url;
}

function redirect($url)
{
    if (headers_sent())
    {
	/**
	* @todo give debug warning
	*/
	trigger_error('Headers already sent, redirect cannot be used', E_WARNING);
    }
    else
    {
	header("Location: $url");
	die();
    }
}

function __($text, $domain = 'default')
{
    global $lang_domains;

    if (isset($lang_domains[$domain][$text]))
    {
	return $lang_domains[$domain][$text];
    }
    else
    {
	return '{' . $text . '}';
    }
}

function _e($text, $domain = 'default')
{
    echo __($text, $domain);
}

function load_langdomain($directory_path, $domain)
{
    global $config, $lang_domains, $user;
    if (empty($domain))
    {
	return false;
    }
    if (!isset($lang_domains) || !is_array($lang_domains))
    {
	$lang_domains = array();
    }

    if (isset($lang_domains[$domain]))
    {
	trigger_error("Language domain $domain has already been initialized. Old domain will be overwritten", E_NOTICE);
	unset($lang_domains[$domain]);
    }

    if (substr($directory_path, -1) == '/')
    {
	$directory_path = substr($directory_path, 0, -1);
    }

    if (file_exists($directory_path . '/' . $user->data['language_name'] . '.php'))
    {
	unset($lang);
	include($directory_path . '/' . $user->data['language_name'] . '.php');
	$lang_domains[$domain] = $lang;
    }
    // Let's try english :3
    else if (file_exists($directory_path . '/en_US.php'))
    {
	unset($lang);
	include($directory_path . '/en_US.php');
	$lang_domains[$domain] = $lang;
    }
    // Also try board's default language
    else if (file_exists($directory_path . '/' . $config['default_langauge'] . '.php'))
    {
	unset($lang);
	include($directory_path . '/' . $config['default_langauge'] . '.php');
	$lang_domains[$domain] = $lang;
    }
    else
    {
	trigger_error('The language file could not been initialized', E_WARNING);
	return false;
    }

    return true;
}

/**
* Html meta refresh
*
* @param string $url Refresh url
* @param int $delay Refresh delay
*
* @return void
*/
function meta_refresh($url, $delay = 3)
{
    $meta = '<meta http-equiv="refresh" content="' . $delay . ',url=' . $url . '">';
    add_action('mr_head', create_function('', "echo '$meta';"));
}

function is_admin_panel()
{
    return (defined('IN_ADMIN'));
}

function msg_handler($errno, $msg_text, $errfile, $errline)
{
    global $auth, $config, $mangareader_root_path, $user;

    // Do not display notices if we suppress them via @
    if (error_reporting() == 0 && $errno != E_USER_ERROR && $errno != E_USER_WARNING && $errno != E_USER_NOTICE)
    {
	return;
    }

    if (!defined('E_DEPRECATED'))
    {
	define('E_DEPRECATED', 8192);
    }

    switch ($errno)
    {
	case E_NOTICE:
	case E_WARNING:

	    // Check the error reporting level and return if the error level does not match
	    // If DEBUG is defined the default level is E_ALL
	    if (($errno & ((defined('DEBUG')) ? E_ALL : error_reporting())) == 0)
	    {
		return;
	    }

	    $errfile = filter_root_path($errfile);
	    $msg_text = filter_root_path($msg_text);

	    $error_name = ($errno === E_WARNING) ? 'PHP Warning' : 'PHP Notice';

	    echo '<b>[MangaReader Debug] ' . $error_name . '</b>: in file <b>' . $errfile . '</b> on line <b>' . $errline . '</b>: <b>' . $msg_text . '</b><br />' . "\n";

	    return;
	break;

	case E_USER_ERROR:
	    if (function_exists('__'))
	    {
		$msg_text = __($msg_text);
		$msg_title = (!isset($msg_title)) ? __('GENERAL_ERROR') : __($msg_title);

		$l_return_index = sprintf(__('RETURN_INDEX'), '<a href="' . $mangareader_root_path . '">', '</a>');
		$l_notify = '';

		if (!empty($config['board_contact']))
		{
		    $l_notify = '<p>' . sprintf(__('NOTIFY_ADMIL_EMAIL'), $config['board_contact']) . '</p>';
		}
	    }
	    else
	    {
		$msg_title = 'General Error';
		$l_return_index = '<a href="' . $mangareader_root_path . '">Return to index page</a>';
		$l_notify = '';
		if (!empty($config['board_contact']))
		{
		    $l_notify = '<p>Please notify the board administrator or webmaster: <a href="mailto:' . $config['board_contact'] . '">' . $config['board_contact'] . '</a></p>';
		}
	    }

	    $log_text = $msg_text;

	    /**
	    * @todo backtrace
	    */

	    /**
	    * @todo to here
	    */

	    // Do not send 200 OK, but service unavailable on errors
	    send_status_line(503, 'Service Unavailable');

	    /**
	    * @todo html output
	    */
	break;

	case E_USER_WARNING:
	case E_USER_NOTICE:

	    if (empty($user->sid))
	    {
		$user->session_begin();
	    }

	    // Re-initialize acl
	    $auth->acl($user->data);

	    if ($msg_text == 'NO_MANGA' || $msg_text == 'NO_CATEGORY' || $msg_text == 'NOT_FOUND')
	    {
		send_status_line(404, 'Not Found');
	    }

	    $msg_text = __($msg_text);
	    $msg_title = (!isset($msg_title)) ? __('INFORMATION') : __($msg_title);

	    /**
	    * @todo Check also if user has permission to reach administration panel
	    */
	    if (defined('IN_ADMIN'))
	    {
		/**
		* @todo set page title as $msg_title
		*/
		get_admin_header();
	    }
	    else
	    {
		/**
		* @todo set page title as $msg_title
		*/
		get_header();
	    }

	    /**
	    * @todo Replace these into functions
	    */
	    $GLOBALS['msg_title'] = $msg_title;
	    $GLOBALS['msg_text'] = $msg_text;
	    $GLOBALS['is_user_warning'] = ($errno == E_USER_WARNING);
	    $GLOBALS['is_user_notice'] = ($errno == E_USER_NOTICE);


	    /**
	    * @todo Check also if user has permission to reach administration panel
	    */
	    if (defined('IN_ADMIN'))
	    {
		locate_admin_template('message_box.php', true);
		get_admin_footer();
	    }
	    else
	    {
		locate_template('message_box.php', true);
		get_footer();
	    }

	    exit_handler();

	    break;
    }
}

/**
* Outputs correct status line header.
*
* Depending on php sapi one of the two following forms is used:
*
* Status: 404 Not Found
*
* HTTP/1.x 404 Not Found
*
* HTTP version is taken from HTTP_VERSION environment variable,
* and defaults to 1.0.
*
* Sample usage:
*
* send_status_line(404, 'Not Found');
*
* @param int $code HTTP status code
* @param string $message Message for the status code
* @return void
*/
function send_status_line($code, $message)
{
    if (substr(strtolower(@php_sapi_name()), 0, 3) === 'cgi')
    {
	// in theory, we shouldn't need that due to php doing it. Reality offers a differing opinion, though
	header("Status: $code $message", true, $code);
    }
    else
    {
	if (!empty($_SERVER['SERVER_PROTOCOL']))
	{
	    $version = $_SERVER['SERVER_PROTOCOL'];
	}
	else
	{
	    $version = 'HTTP/1.0';
	}

	header("$version $code $message", true, $code);
    }
}

/**
* Remove absolute path from error messages
*
* @param string $errfile Absolute root path
*
* @return Relative file path
*/
function filter_root_path($errfile)
{
    static $root_path;

    if (empty($root_path))
    {
	$root_path = realpath(dirname(__FILE__) . '/../');
    }

    return str_replace(array($root_path, '\\'), array('[ROOT]', '/'), $errfile);
}

/**
* Module loader
* Currently used only in ucp
*
* @param Module group (eg, ucp)
* @param Module name (eg, register)
*
* @return void
*/
function load_module($module_group, $module_name)
{
    global $mode, $id, $mangareader_root_path;

    if (!isset($mode))
    {
	$mode = request_var('mode', '');
    }
    if (!isset($id))
    {
	$id = request_var('id', 0);
    }

    $module_file = $mangareader_root_path . 'includes/' . $module_group . '/' . $module_group . '_' . $mode . '.php';

    if (!file_exists($module_file))
    {
	// Somehow this line gives Invalid error type specified error
	trigger_error("$module_name module of $module_group does not exist", E_WARNING);
    }

    include_once($module_file);

    $m_name = $module_group . '_' . $module_name;
    $module = new $m_name();
    $module->main($id, $mode);
}

?>

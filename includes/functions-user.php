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
* Hashes an email address to a big integer
*
* @param string $email Email address
*
* @return string Unsigned Big Integer
*/
function mangareader_email_hash($email)
{
    return sprintf('%u', crc32(strtolower($email))) . strlen($email);
}

function hash_password($password)
{
    global $config;

    // Let's make a sandwich with password and board salt
    $password = $config['board_salt'] . $password . $config['board_salt'];

    return preg_replace('/=+$/', '', base64_encode(pack('H*', md5($password))));
}

function validate_data($data, $val_ary)
{
    global $user;

    $error = array();

    foreach ($val_ary as $var => $val_seq)
    {
	if (!is_array($val_seq[0]))
	{
	    $val_seq = array($val_seq);
	}

	foreach ($val_seq as $validate)
	{
	    $function = array_shift($validate);
	    array_unshift($validate, $data[$var]);

	    if ($result = call_user_func_array('validate_' . $function, $validate))
	    {
		$error[] = (empty($user->lang[$result . '_' . strtoupper($var)])) ? $result : $result . '_' . strtoupper($var);
	    }
	}
    }

    return $error;
}

/**
* Validate String
*
* @return boolean|string Either false if validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_string($string, $optional = false, $min = 0, $max = 0)
{
    if (empty($string) && $optional)
    {
	return false;
    }

    if ($min && utf8_strlen(htmlspecialchars_decode($string)) < $min)
    {
	return 'TOO_SHORT';
    }

    if ($max && utf8_strlen(htmlspecialchars_decode($string)) > $max)
    {
	return 'TOO_LONG';
    }

    return false;
}

/**
* Validate Number
*
* @return boolean|string Either false if the validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_number($number, $optional = false, $min = 0, $max = 0)
{
    if (empty($num) && $optional)
    {
	return false;
    }

    if ($min && $number < $min)
    {
	return 'TOO_SMALL';
    }

    if ($max && $number > $max)
    {
	return 'TOO_LARGE';
    }

    return false;
}

/**
* Validate Match
*
* @return boolean|string Either false if the validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_match($string, $optional = false, $match = '')
{
    if (empty($string) && $optional)
    {
	return false;
    }

    if (empty($match))
    {
	return false;
    }

    if (!preg_match($string, $match))
    {
	return 'WRONG_DATA';
    }

    return false;
}

?>

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
		$error[] = $result . '_' . strtoupper($var);
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
function validate_num($number, $optional = false, $min = 0, $max = 0)
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

/**
* Validate Date
*
* @return boolean|string Either false if the validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_date($string, $optional = false)
{
    $date = explode('-', $string);
    if ((empty($date) || sizeof($date) != 3) && $optional)
    {
	return false;
    }
    else if ($optional)
    {
	for ($field = 0; $field <= 1; $field++)
	{
	    $date[$field] = (int) $date[$field];
	    if (empty($date[$field]))
	    {
		$date[$field] = 1;
	    }
	}
	$date[2] = (int) $date[2];

	// assume an arbitrary leap year
	if (empty($date[2]))
	{
	    $date[2] = 1980;
	}
    }

    if (sizeof($date) != 3 || !checkdate($date[1], $date[0], $date[2]))
    {
	return 'INVALID';
    }

    return false;
}

/**
* Validate username
*
* @todo Make this function better
* @return boolean|string Either false if the validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_username($username)
{
    global $db;

    $clean_username = utf8_clean_string($username);

    // ... fast checks first.
    if (strpos($username, '&quot;') !== false || strpos($username, '"') !== false || empty($clean_username))
    {
	return 'INVALID_CHARS';
    }

    $sql = 'SELECT username
	    FROM ' . USERS_TABLE . '
	    WHERE username_clean = ' . $db->quote($clean_username);
    $row = $db->query($sql)->fetch();

    if ($row)
    {
	return 'USERNAME_TAKEN';
    }

    return false;
}

/**
* Validate Email
*
* @return boolean|string Either false if the validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_email($email)
{
    $email = strtolower($email);

    // Regex written by James Watts and Francisco Jose Martin Moreno
    // http://fightingforalostcause.net/misc/2006/compare-email-regex.php
    $email_regex = '([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,63})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)';

    if (!preg_match('/^' . $email_regex . '$/i', $email))
    {
	return 'EMAIL_INVALID';
    }

    // Check MX record.
    // The idea for this is from reading the UseBB blog/announcement. :)
    if ($config['email_check_mx'])
    {
	list(, $domain) = explode('@', $email);

	if (checkdnsrr($domain, 'A') === false && checkdnsrr($domain, 'MX') === false)
	{
	    return 'DOMAIN_NO_MX_RECORD';
	}
    }

    if (!$config['allow_emailreuse'])
    {
	global $db;

	$sql = 'SELECT user_email_hash
		FROM ' . USERS_TABLE . '
		WHERE user_email_hash = ' . $db->quote(mangareader_email_hash($email));
	$row = $db->query($sql)->fetch();
	if ($row)
	{
	    return 'EMAIL_TAKEN';
	}
    }
}

function user_add($user_row)
{
    global $db;

    if (empty($user_row['username']) || empty($user_row['email']) || empty($user_row['password']) || empty($user_row['group_id']))
    {
	return false;
    }

    $username_clean = utf8_clean_string($user_row['username']);

    if (empty($username_clean))
    {
	return false;
    }

    $sql_ary = array(
	'username'	=> $user_row['username'],
	'group_id'	=> $user_row['group_id'],
	'username_clean'=> $username_clean,
	'user_password'	=> hash_password($user_row['password']),
	'user_email'	=> strtolower($user_row['email']),
	'user_email_hash'=> mangareader_email_hash($user_row['email']),
	'user_ip'	=> get_ip(),
	'user_regdate'	=> time(),
	'user_timezone'	=> $user_row['tz'],
    );

    $sql = 'INSERT INTO ' . USERS_TABLE . ' ' . $db->build_array('INSERT', $sql_ary);

    if (!$db->query($sql))
    {
	/**
	* @todo registration error
	*/
	return false;
    }

    /**
    * @todo registration ok message
    */
    return $db->lastInsertId();
}

?>

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
function phpbb_email_hash($email)
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

function is_serialized($data)
{
    return (@unserialize($data) !== false);
}

?>

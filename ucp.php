<?php
/**
*
* @package ucp
* @version $Id$
* @copyright Copyright (c) 2013, Firat Akandere
* @author Firat Akandere <f.akandere@gmail.com>
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License, version 3
*
*/

/**
* @ignore
*/
define('IN_MANGAREADER', true);
$mangareader_root_path = (defined('MANGAREADER_ROOT_PATH')) ? MANGAREADER_ROOT_PATH : './';
include($mangareader_root_path . 'common.php');

$mode = request_var('mode', '');

switch ($mode)
{
    case 'login':
        locate_template('user_login.php', true);
    break;

    case 'register':
        locate_template('user_register.php', true);
    break;
    default:

    break;
}
?>

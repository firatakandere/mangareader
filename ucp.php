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
include_once($mangareader_root_path . 'includes/functions-user.php');

$mode = request_var('mode', '');
$submit = (isset($_POST['submit'])) ? true : false;

switch ($mode)
{
    case 'login':
        locate_template('user_login.php', true);
    break;

    case 'register':
        if ($config['register_open'] != 1)
        {
            /**
            * @todo make here better
            */
            trigger_error('registers are not open atm', E_USER_WARNING);
        }
        if (isset($_REQUEST['not_agreed']) || $user->data['user_id'] != ANONYMOUS)
        {
            redirect(generate_url('index.php', ''));
        }

        if ($submit)
        {
            $data = array(
                'username'          => utf8_normalize_nfc(request_var('username', '', true)),
                'password'          => request_var('password', '', true),
                'password_confirm'  => request_var('password_confirm', '', true),
                'email'             => request_var('email', ''),
                'email_confirm'     => request_var('email_confirm', ''),
            );
        }

        locate_template('user_register.php', true);
    break;
    default:

    break;
}
?>

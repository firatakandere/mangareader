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

$user->session_begin();
$auth->acl($user->data);
$user->setup();

$mode = request_var('mode', '');
$submit = (isset($_POST['submit'])) ? true : false;
$redirect = request_var('redirect', generate_url('', ''));
$error = array();

switch ($mode)
{
    case 'login':

        if ($user->data['user_id'] != ANONYMOUS)
        {
            redirect(generate_url('', '')); //redirect to home page
        }

        $data['username'] = utf8_normalize_nfc(request_var('username', '', true));
        $data['password'] = utf8_normalize_nfc(request_var('password', '', true));

        if ($submit)
        {
            if (empty($data['username']))
            {
                $error[] = 'LOGIN_EMPTY_USERNAME';
            }
            if (empty($data['password']))
            {
                $error[] = 'LOGIN_EMPTY_PASSWORD';
            }

            if (!sizeof($error))
            {
                if ($user->login($data['username'], $data['password']))
                {
                    meta_refresh($redirect, 3);
                    trigger_error('LOGIN_SUCCESSFUL');
                }
                else
                {
                    $error[] = 'LOGIN_INVALID';
                }
            }
        }

        locate_template('user_login.php', true);
    break;

    case 'register':
        if (!$config['register_open'])
        {
            trigger_error('REGISTERS_CLOSED', E_USER_WARNING);
        }
        if (isset($_REQUEST['not_agreed']) || $user->data['user_id'] != ANONYMOUS)
        {
            redirect(generate_url('', '')); //redirect to home page
        }

        load_module('ucp', 'register');

        locate_template('user_register.php', true);
    break;

    case 'logout':
        /**
        * @todo Maybe meta refresh?
        */
        $user->logout($redirect);
    break;
    default:

    break;
}
?>

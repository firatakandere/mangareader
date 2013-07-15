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

        $timezone = $config['board_timezone'];

        $data = array(
            'username'          => utf8_normalize_nfc(request_var('username', '', true)),
            'password'          => request_var('password', '', true),
            'password_confirm'  => request_var('password_confirm', '', true),
            'email'             => request_var('email', ''),
            'email_confirm'     => request_var('email_confirm', ''),
            'tz'                => request_var('tz', (float) $timezone),
        );

        if ($submit)
        {
            $error = validate_data($data, array(
                'username'  => array(
                    array('string', false, $config['min_username_chars'], $config['max_username_chars']),
                    array('username', ''),
                ),
                'password'  => array(
                    array('string', false, $config['min_password_chars'], $config['max_password_chars']),
                    //array('password')
                ),
                'password_confirm' => array('string', false, $config['min_password_chars'], $config['max_password_chars']),
                'email'     => array(
                    array('string', false, 6, 60),
                    array('email')
                ),
                'email_confirm' => array('string', false, 6, 60),
                'tz'    => array('num', -14, 14)
            ));

            if (!sizeof($error))
            {
                if ($data['password'] != $data['password_confirm'])
                {
                    $error[] = $user->lang['PASSWORD_MATCH_ERROR'];
                }

                if ($data['email'] != $data['email_confirm'])
                {
                    $error[] = $user->lang['EMAIL_MATCH_ERROR'];
                }
            }
        }

        locate_template('user_register.php', true);
    break;
    default:

    break;
}
?>

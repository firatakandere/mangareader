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
if (!defined('IN_MANGAREADER'))
{
    exit;
}

/**
* Registration class
* @package ucp
*/
class ucp_register
{
    public function main($id, $mode)
    {
        global $data, $config, $error, $submit;

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
                    $error[] = 'PASSWORD_MATCH_ERROR';
                }

                if ($data['email'] != $data['email_confirm'])
                {
                    $error[] = 'EMAIL_MATCH_ERROR';
                }
            }

            if (!sizeof($error))
            {
                if ($config['activation_required'] == USER_ACTIVATION_SELF || $config['activation_required'] == USER_ACTIVATION_ADMIN)
                {
                    $data['group_id'] = INACTIVE_USERS;
                }
                else
                {
                    $data['group_id'] = REGISTERED_USERS;
                }

                if (user_add($data) !== false)
                {
                    /**
                    * @todo registration ok
                    */
                }
                else
                {
                    /**
                    * @todo registration failed
                    */
                }
            }
        }
    }
}

?>

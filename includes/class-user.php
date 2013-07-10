<?php
/**
*
* @package hooks
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

class User
{
    var $data = array();
    var $lang = array();
    var $lang_name = '';
    var $sid;

    function __construct()
    {
        global $config, $db;

        // Let's kill expired sessions first
        $expiration = time() - ($config['session_span'] * 60);
        $sql = 'DELETE FROM ' . SESSION_TABLE . '
                WHERE session_time < ' . (int)$expiration;
        $db->query($sql);
        //

        if ((!isset($_SESSION) || !is_array($_SESSION)) && !headers_sent())
        {
            session_start();
        }

        $this->sid = session_id();

        // Defaults
        $this->data = array(
            'user_id'       => ANONYMOUS,
            'template_path' => $config['default_template'],
            'language_path'      => 'en_US',
        );

        if (isset($_COOKIE[$config['session_key']]) && !isset($_SESSION[$config['session_key']]))
        {
            $_SESSION[$config['session_key']] = $_COOKIE[$config['session_key']];
        }

        if (isset($_SESSION[$config['session_key']]))
        {
            // try to unserialize data
            if (($u_data = @unserialize($_SESSION[$config['session_key']])) !== false)
            {
                if (!$this->load($u_data['username'], $u_data['password']))
                {
                    $this->logout();
                }
            }
            else
            {
                $this->logout();
            }
        }
    }

    function logout($redirect = '')
    {
        global $config;
        unset($_SESSION[$config['session_key']]);
        setcookie($config['session_key'], '', time() - 3600, '/');
        if (!empty($redirect) && !headers_sent())
        {
            header("Location: $redirect");
        }
    }

    function login($username = '', $password = '', $auto_login = false)
    {
        global $config;
        if ($this->load($username, $password))
        {
            $data = serialize(array('username' => $username, 'password' => $password));
            $_SESSION[$config['session_key']] = $data;

            if ($auto_login)
            {
                /**
                * @todo Get the expiration time from config array
                */
                setcookie($config['session_key'], $data, time() + 60 * 60 * 24 * 30, '/');
            }

            return true;
        }

        return false;
    }

    function load($username = '', $password = '')
    {
        if (empty($username))
        {
            return false;
        }

        global $db;

        $sql = 'SELECT *
                FROM ' . USER_TABLE . '
                WHERE username = ' . $db->quote($username);
        $result = $db->query($sql);

        if ($result === false)
        {
            return false;
        }

        $row = $result->fetch(PDO::FETCH_ASSOC);

        if (!function_exists('hash_password'))
        {
            global $mangareader_root_path;
            include($mangareader_root_path . 'includes/functions-user.php');
        }

        if ($row['user_password'] != hash_password($password))
        {
            return false;
        }

        unset($row['hash_password']);
        $this->data = $row;

        return true;
    }
}

?>

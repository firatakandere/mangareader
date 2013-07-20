<?php
/**
*
* @package acl
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
* User Management Class
* @package acl
*/
class User
{
    var $data = array();
    var $sid;

    /**
    * Constructor
    */
    public function __construct()
    {
        global $config, $db;

        // Let's kill expired sessions first
        $expiration = time() - ((int) $config['session_span'] * 60);
        $sql = 'DELETE FROM ' . SESSIONS_TABLE . '
                WHERE session_last_visit < ' . (int)$expiration;
        $db->query($sql);
        //

        // Start session if has not started yet
        if ((!isset($_SESSION) || !is_array($_SESSION)) && !headers_sent())
        {
            session_start();
        }

        $this->sid = session_id();

        // Load default user data
        $this->load_defaults();

        // Check if session id already exists on database
        $sql = 'SELECT session_user_id, session_fingerprint
                FROM ' . SESSIONS_TABLE . '
                WHERE session_id = ' . $db->quote($this->sid);
        $row = $db->query($sql)->fetch();
        if (!empty($row))
        {
            // Check fingerprint
            if ($row['session_fingerprint'] != get_browser_fingerprint())
            {
                $this->kill_session();
                $this->logout();
                if ($row['session_user_id'] == ANONYMOUS)
                {
                    $this->add_session();
                }
            }
            else
            {
                // Update session last visit time
                $sql = 'UPDATE ' . SESSIONS_TABLE . '
                        SET session_last_visit = ' . time() . '
                        WHERE session_id = ' . $db->quote($this->sid);
                $db->query($sql);
            }
        }
        else
        {
            $this->add_session();
        }

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

    public function logout($redirect = '')
    {
        global $config, $db;
        unset($_SESSION[$config['session_key']]);
        setcookie($config['session_key'], '', time() - 3600, '/');
        $this->kill_session();
        $this->load_defaults();
        if (!empty($redirect))
        {
            redirect($redirect);
        }
    }

    public function kill_session()
    {
        global $db;
        setcookie($config['session_key'], '', time() - 3600, '/');
        $sql = 'DELETE FROM ' . SESSIONS_TABLE . ' WHERE session_id = ' . $db->quote($this->sid);
        $db->query($sql);
    }

    public function login($username = '', $password = '', $auto_login = false)
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

    private function load($username = '', $password = '')
    {
        if (empty($username))
        {
            return false;
        }

        global $db;

        $sql = 'SELECT *
                FROM ' . USERS_TABLE . '
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

        unset($row['user_password']);
        $this->data = $row;

        // Session should already exists in database
        $sql = 'UPDATE ' . SESSIONS_TABLE . '
                SET session_user_id = ' . $this->data['user_id'] . '
                WHERE session_id = ' . $db->quote($this->sid);
        $db->query($sql);

        return true;
    }

    private function add_session()
    {
        global $db;
        $sql_ary = array(
            'session_id'        => $this->sid,
            'session_user_id'   => $this->data['user_id'],
            'session_last_visit'=> time(),
            'session_start'     => time(),
            'session_fingerprint'=> get_browser_fingerprint()
        );
        $sql = 'INSERT INTO ' . SESSIONS_TABLE . ' ' . $db->build_array('INSERT', $sql_ary);
        if ($db->query($sql) !== false)
        {
            return true;
        }

        return false;
    }

    private function load_defaults()
    {
        global $config, $db;
        $sql = 'SELECT *
                FROM ' . USERS_TABLE . '
                WHERE user_id = ' . (int) ANONYMOUS;
        $sth = $db->prepare($sql);
        $sth->execute();
        $this->data = $sth->fetch(PDO::FETCH_ASSOC);
        $this->data['template_path'] = $config['default_template'];
        $this->data['language_name'] = $config['default_language'];
    }
}

?>

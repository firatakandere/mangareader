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
    * Session begin
    */
    public function session_begin()
    {
        global $config, $db;

        // Start session if has not started yet
        if ((!isset($_SESSION) || !is_array($_SESSION)) && !headers_sent())
        {
            session_start();
        }

        $this->sid = session_id();

        // Check if session_id already exists on database
        $sql = 'SELECT session_user_id, session_fingerprint
                FROM ' . SESSIONS_TABLE . '
                WHERE session_id = ' . $db->quote($this->sid);
        $row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (!empty($row))
        {
            if ($row['session_fingerprint'] == get_browser_fingerprint())
            {
                $sql = 'UPDATE ' . SESSIONS_TABLE . '
                        SET session_last_visit = ' . (int) time() . '
                        WHERE session_id = ' . $db->quote($this->sid);
                $db->query($sql);

                $this->load_user_data($row['session_user_id']);
            }
            else
            {
                // Probably session hijacking. Act as a guest
                $this->load_user_data(ANONYMOUS);
            }
        }
        else
        {
            $this->add_session();
            $this->load_user_data(ANONYMOUS);
        }
    }

    /**
    * @todo Improve this function
    */
    public function setup()
    {
        global $lang_domains, $mangareader_root_path;
        load_langdomain($mangareader_root_path . 'languages', 'default');
    }

    public function logout($redirect = '')
    {
        global $config, $db;

        // Kill session
        $sql = 'DELETE FROM ' . SESSIONS_TABLE . ' WHERE session_id = ' . $db->quote($this->sid);
        $db->query($sql);

        if (empty($redirect))
        {
            $redirect = generate_url('', '');
        }

        redirect($redirect);
    }

    /**
    * @todo add auto login feature
    */
    public function login($username = '', $password = '', $auto_login = false)
    {
        global $config, $db;

        if (empty($username))
        {
            return false;
        }

        $sql = 'SELECT *
                FROM ' . USERS_TABLE . '
                WHERE username_clean = ' . $db->quote(utf8_clean_string($username));
        $row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($row))
        {
            return false;
        }

        if (!function_exists('hash_password'))
        {
            global $mangareader_root_path;
            include($mangareader_root_path . 'includes/functions-user.php');
        }

        if ($row['user_password'] != hash_password($password))
        {
            return false;
        }

        $sql = 'UPDATE ' . SESSIONS_TABLE . '
                SET session_user_id = ' . $row['user_id'] . '
                WHERE session_id = ' . $db->quote($this->sid);
        $db->query($sql);

        $this->load_user_data($row['user_id'], $row);
        return true;
    }

    private function load_user_data($user_id, $pre_data = false)
    {
        global $config, $db;

        if ($pre_data === false)
        {
            $sql = 'SELECT *
                    FROM ' . USERS_TABLE . '
                    WHERE user_id = ' . (int) $user_id;
            $row = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $row = $pre_data;
        }

        if (!empty($row))
        {
            unset($row['user_password']);
            $row['template_path'] = (!empty($row['template_path'])) ? $row['template_path'] : $config['default_template'];
            $row['language_name'] = (!empty($row['language_name'])) ? $row['language_name'] : $config['default_language'];
            $row['user_timezone'] = (!empty($row['user_timezone'])) ? $row['user_timezone'] : $config['board_timezone']; // fix here
            $this->data = $row;
        }
        /**
        * @todo user not found debug
        */
    }

    private function add_session($user_id = ANONYMOUS)
    {
        global $db;

        $sql_ary = array(
            'session_id'        => $this->sid,
            'session_user_id'   => $user_id,
            'session_last_visit'=> time(),
            'session_start'     => time(),
            'session_fingerprint'=> get_browser_fingerprint()
        );

        $sql = 'INSERT INTO ' . SESSIONS_TABLE . ' ' . $db->build_array('INSERT', $sql_ary);
        $db->query($sql);
    }
}

?>

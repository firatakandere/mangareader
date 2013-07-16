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
* Access control list
* @package reader
*/
class Acl extends User
{
    private $roles;

    public function __construct()
    {
        parent::__construct();
    }

    public function hasPrivilege($perm)
    {
        foreach ($this->roles as $role)
        {
            if ($role->hasPerm($perm))
            {
                return true;
            }
        }
        return false;
    }

    public static function getByUsername($username)
    {
        global $db;
        $username_clean = utf8_clean_string($username);

        $sql = 'SELECT *
                FROM ' . USERS_TABLE . '
                WHERE username_clean = ' . $db->quote($username_clean);
        $sth = $db->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();

        if (!empty($result))
        {
            $acl = new Acl();
            unset($result[0]['user_password']);
            $acl->data = $result[0];
            $acl->initRoles();
            return $acl;
        }

        return false;
    }

    protected function initRoles()
    {
        global $db;
        $this->roles = array();

        $sql = 'SELECT t1.group_id
                FROM ' . USERS_TABLE . ' AS t1
                JOIN ' . GROUPS_TABLE . ' AS t2
                ON t1.group_id = t2.group_id
                WHERE t1.user_id = ' . $this->data['user_id'];
        $sth = $db->prepare($sql);

        if (!class_exists('Group'))
        {
            global $mangareader_root_path;
            include_once($mangareader_root_path . 'includes/class.group.php');
        }

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $this->roles[$row['group_name']] = Group::getGroupPerms($row['group_id']);
        }
    }
}
?>

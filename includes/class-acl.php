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
* Access control list
* @package acl
*/
class Acl extends User
{
    private $roles;

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Permission checker
    *
    * @param string $perm Permission name
    * @return boolean Either true if the user has permission, or false if the permission denied
    */
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

    /**
    * Get Privileges by username
    *
    * @param string $username Username
    * @return mixed Either new Acl object if username exists, otherwise false
    */
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

    /**
    * Initialize roles
    */
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

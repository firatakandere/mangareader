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
*
* Every group has their own ACL
* Also users' ACL can be changed specifically
* When a permission is checked; user's own permission and user's group's permission will be compared
* If user's permission says NO but user's group's permission says YES; comparison result will be YES, or vice-versa
* If one of the permission is NEVER, comparison result will be NO
*
* There are three types of permissions,
* Yes; which gives permission
* No; which does not give permission
* Never; which NEVER gives permission
*
* "Yes" will be stored as "1" (integer) in database
* "No" will not be stored in database, if the row does not exist, the system will know it's "No"
* "Never" will be stored as "0" (integer) in database
*
* @package acl
*/
class Auth
{
    private $group_permissions;
    private $user_permissions;
    private $data;

    /**
    * Authorize user data
    */
    public function acl($user_data)
    {
        // We need only these two
        $this->data['user_id'] = $user_data['user_id'];
        $this->data['group_id'] = $user_data['group_id'];

        $this->group_permissions = array();
        $this->user_permissions = array();

        $this->init_group_permissions();
        $this->init_user_permissions();
    }

    public function has_perm($perm_name)
    {
        $group_p = (isset($this->group_permissions[$perm_name])) ? (int) $this->group_permissions[$perm_name] : false;
        $user_p  = (isset($this->user_permissions[$perm_name])) ? (int) $this->user_permissions[$perm_name] : false;

        // If one of them is 0 (never), return false
        if ($group_p === 0 || $user_p === 0)
        {
            return false;
        }

        // If one of them is 1 (yes), return true
        if ($group_p === 1 || $user_p === 1)
        {
            return true;
        }

        // Otherwise, both of them are false (no), return false
        return false;
    }

    /**
    * Initialize permissions of user's group
    */
    private function init_group_permissions()
    {
        global $db;

        $sql = 'SELECT perm_name, perm_type
                FROM ' . PERMISSIONS_TABLE . ' AS t1
                JOIN ' . GROUP_PERM_TABLE . ' AS t2
                ON t1.perm_id = t2.perm_id
                WHERE t2.group_id = ' . $this->data['group_id'];
        $sth = $db->prepare($sql);
        $sth->execute();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $this->group_permissions[$row['perm_name']] = $row['perm_type'];
        }
    }

    /**
    * Initialize user's own permissions
    */
    private function init_user_permissions()
    {
        global $db;

        $sql = 'SELECT perm_name, perm_type
                FROM ' . PERMISSIONS_TABLE . ' AS t1
                JOIN ' . USER_PERM_TABLE . ' AS t2
                ON t1.perm_id = t2.perm_id
                WHERE t2.user_id = ' . $this->data['user_id'];
        $sth = $db->prepare($sql);
        $sth->execute();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $this->user_permissions[$row['perm_name']] = $row['perm_type'];
        }
    }
}
?>

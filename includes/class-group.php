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
* Acl-Group class
* @package reader
*/
class Group
{
    protected $permissions;

    protected function __construct()
    {
        $this->permissions = array();
    }

    public static function getGroupPerms($group_id)
    {
        global $db;

        if (!is_int($group_id))
        {
            return false;
        }

        $group = new Group();

        $sql = 'SELECT t2.perm_name
                FROM ' . GROUP_PERM_TABLE ' AS t1
                JOIN ' . PERMISSIONS_TABLE . ' AS t2
                ON t1.perm_id = t2.perm_id
                WHERE t1.group_id = ' . $group_id;
        $sth = $db->prepare($sql);
        $sth->execute();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $group->permissions[$row['perm_name']] = true;
        }

        return $group;
    }

    public function hasPerm($permission)
    {
        return (isset($this->permissions[$permission]));
    }
}

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
if (!defined('IN_MANGAREADER') || !defined('IN_INSTALL'))
{
    exit;
}

function load_permissions($perm_ary)
{
    global $db;

    foreach ($perm_ary as $permission)
    {
        $sql = 'INSERT INTO ' . PERMISSIONS_TABLE . ' perm_name VALUES (' . $db->quote($permission) . ')';
        $db->query($sql);
    }
}

function load_group_permissions($group_perm_ary)
{
    global $db;

    $perms = array();

    $sql = 'SELECT *
            FROM ' . PERMISSIONS_TABLE;
    $sth = $db->prepare($sql);
    $sth->execute();

    while ($row = $sth->fetch(PDO::FETCH_ASSOC))
    {
        $perms[$row['perm_name']] = $row['perm_id'];
    }

    foreach ($group_perm_ary as $group_id => $permissions)
    {
        foreach ($permissions as $perm_name => $value)
        {
            $sql_ary = array(
                'group_id'  => $group_id,
                'perm_id'   => $perms[$perm_name]
            );
            $sql = 'INSERT INTO ' . GROUP_PERM_TABLE . ' ' . $db->build_array('INSERT', $sql_ary);
            $db->query($sql);
        }
    }
}

?>

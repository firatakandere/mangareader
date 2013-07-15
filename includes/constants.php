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

define('ANONYMOUS', 0);

define('CONFIG_TABLE', $dbprefix . 'config');
define('MANGA_TABLE', $dbprefix . 'manga');
define('CATEGORY_TABLE', $dbprefix . 'category');
define('USERS_TABLE', $dbprefix . 'users');

?>

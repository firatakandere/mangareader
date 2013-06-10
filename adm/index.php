<?php
/**
*
* @package adm
* @version $Id$
* @copyright Copyright (c) 2013, Firat Akandere
* @author Firat Akandere <f.akandere@gmail.com>
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License, version 3
*
*/

/**
* @ignore
*/
define('IN_MANGAREADER', true);
$mangareader_root_path = (defined('MANGAREADER_ROOT_PATH')) ? MANGAREADER_ROOT_PATH : './../';
require($mangareader_root_path . 'common.php');

$mangareader_admin_root_path = (defined('MANGAREADER_ADMIN_ROOT_PATH')) ? MANGAREADER_ADMIN_ROOT_PATH : './';

$template->set_custom_template($mangareader_admin_root_path . 'style/', 'admin');


?>
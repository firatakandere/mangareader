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
define('IN_MANGAREADER', true);
$mangareader_root_path = (defined('MANGAREADER_ROOT_PATH')) ? MANGAREADER_ROOT_PATH : './';
include($mangareader_root_path . 'common.php');

$user->session_begin(); // Start user session
$auth->acl($user->data); // Authorize user data
$user->setup();  // Load language

locate_template('index.php', true);

?>

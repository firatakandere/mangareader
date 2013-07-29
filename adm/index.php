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
define('IN_ADMIN', true);
$mangareader_root_path = (defined('MANGAREADER_ROOT_PATH')) ? MANGAREADER_ROOT_PATH : './../';
$mangareader_admin_root_path = (defined('MANGAREADER_ADMIN_ROOT_PATH')) ? MANGAREADER_ADMIN_ROOT_PATH : './';
require($mangareader_root_path . 'common.php');
include_once($mangareader_root_path . 'includes/functions-admin.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

$page = request_var('page', '');
$subpage = request_var('subpage', '');

// Prepare menu pages
do_action('admin_menu_pages');

register_script('bootstrap', get_admin_template_directory_uri(true) . '/js/bootstrap.min.js', array('jquery'));
enqueue_script('bootstrap', true);

get_admin_header();
get_admin_sidebar();

load_hook($page, $subpage);

get_admin_footer();
?>

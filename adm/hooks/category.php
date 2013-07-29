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
if (!defined('IN_MANGAREADER') || !defined('IN_ADMIN'))
{
    exit;
}

add_action('admin_menu_pages', 'add_default_category');

function add_default_category()
{
    add_menu_page(__('CATEGORIES'), __('CATEGORIES'), 'can_view_categories', 'category', 'default_category', 5);
    add_submenu_page('category', __('NEW_CATEGORY'), __('NEW_CATEGORY'), 'can_add_categories', 'add_category', 'default_add_category', 1);
}

function default_category()
{

}

function default_add_category()
{
    $js_script = <<<EOD
<script>
$(\'.btn-is-adult\').bind(\'click\', function(){
    $(\'input[name="is_adult"]\').val(this.value);
});
</script>

EOD;
    add_action('mr_footer', create_function('', "echo '$js_script';"));

    global $mangareader_admin_root_path;
    include_once($mangareader_admin_root_path . 'hooks/category_styles/new_category.php');
}

function get_new_category_uri()
{
    global $page, $subpage;
    generate_page_url($page, $subpage);
}

?>

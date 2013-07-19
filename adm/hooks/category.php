<?php

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
    global $mangareader_admin_root_path;

    include_once($mangareader_admin_root_path . 'hooks/category_styles/new_category.php');
}

?>

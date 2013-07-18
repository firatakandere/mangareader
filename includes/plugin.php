<?php
/**
*
* @package hooks
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
* Add new menu page to admin panel
*
* @param string $menu_title Title for the menu
* @param string $page_title Title for the page
* @todo Add param here after capabilities have done
* @param string $menu_slug Unique menu slug, do not use special characters
* @param string $function The function name which will be called when the page is loaded
* @param int $position Position of the menu item
* @return void
*/
function add_menu_page($menu_title, $page_title, $capability, $menu_slug, $function = '', $position = null)
{
    if (!is_admin_panel())
    {
        return;
    }

    global $admin_pages;

    if (isset($admin_pages[$menu_slug]) && defined('DEBUG'))
    {
        trigger_error("add_menu_page: Menu slug $menu_slug already exists, it will be overwritten.", E_USER_WARNING);
    }

    $admin_pages[$menu_slug] = array(
        'menu_title'    => $menu_title,
        'page_title'    => $page_title,
        'capability'    => $capability,
        'function'      => $function,
        'position'      => $position,
        'subpages'      => array()
    );
}

/**
* Add new sub page to an existing admin menu page
*
* @param string $parent_slug Slug name of parent page
* @param string $menu_title Title for the menu
* @param string $page_title Title for the page
* @todo Add param here after capabilities have done
* @param string $menu_slug Unique menu slug, do not use special characters
* @param string $function The function name which will be called when the page is loaded
* @param int $position Position of the menu item
* @return void
*/
function add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null)
{
    if (!is_admin_panel())
    {
        return;
    }

    global $admin_pages;

    if (!isset($admin_pages[$parent_slug]))
    {
        if (defined('DEBUG'))
        {
            trigger_error("add_submenu_page: Parent slug $parent_slug does not exist.", E_USER_WARNING);
        }
        return false;
    }

    if (isset($admin_pages[$parent_slug]['subpages'][$menu_slug]) && defined('DEBUG'))
    {
        trigger_error("add_submenu_page: Menu slug $menu_slug already exists, it will be overwritten", E_USER_WARNING);
    }

    $admin_pages[$parent_slug]['subpages'][$menu_slug] = array(
        'menu_title'    => $menu_title,
        'page_title'    => $page_title,
        'capability'    => $capability,
        'menu_slug'     => $menu_slug,
        'function'      => $function,
        'position'      => $position
    );
}

/**
* Remove a menu page from admin panel
*
* @param string $menu_slug Slug name of the menu item
* @return boolean Either false if the menu item does not exist or true if it's removed
*/
function remove_menu_page($menu_slug)
{
    if (!is_admin_panel())
    {
        return;
    }

    global $admin_pages;

    if (!isset($admin_pages[$menu_slug]))
    {
        return false;
    }

    unset($admin_pages[$menu_slug]);
    return true;
}

/**
* Remove a submenu page from admin panel
*
* @param string $menu_slug Slug name of the submenu ite
* @return boolean Either false if the menu item does not exist or true if it's removed
*/
function remove_submenu_page($menu_slug, $submenu_slug)
{
    if (!is_admin_panel())
    {
        return;
    }

    global $admin_pages;

    if (!isset($admin_pages[$menu_slug]['subpages'][$submenu_slug]))
    {
        return false;
    }

    unset($admin_pages[$menu_slug]['subpages'][$submenu_slug]);
}

function get_plugin_header_line($title, $content)
{
    if (is_empty($content))
    {
        return false;
    }
    preg_match('/^\s*' . $title . '\s*:\s*(.*)$/i', $input_line, $matches);
    if (sizeof($matches))
    {
        return $matches[1];
    }
    else
    {
        return false;
    }
}

function get_plugin_header($file_path)
{
    if (!file_exists($file_path))
    {
        if (defined('DEBUG'))
        {
            trigger_error("get_plugin_header: File for $file_path does not exist.");
        }
        return false;
    }
}

?>

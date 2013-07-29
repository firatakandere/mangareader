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

function admin_menu_list($return = false)
{
    global $auth, $admin_pages;

    $output = '<ul class="nav nav-list">';

    foreach ($admin_pages as $slug_name => $atts)
    {
        if ($auth->has_perm($atts['capability']))
        {
            $page_url = generate_page_url($slug_name, false, false, true);
            $output .= '<li class="nav-header"><a href="' . $page_url . '">' . $atts['menu_title'] . '</a></li>';
            if (isset($atts['subpages']) && sizeof($atts['subpages']))
            {
                foreach ($atts['subpages'] as $sub_slug_name => $sub_atts)
                {
                    if ($auth->has_perm($sub_atts['capability']))
                    {
                        $subpage_url = generate_page_url($slug_name, $sub_slug_name, false, true);
                        $output .= '<li><a href="' . $subpage_url . '">' . $sub_atts['menu_title'] . '</a></li>';
                    }
                }
            }
        }
    }

    $output .= '</ul>';

    if ($return)
    {
        return $output;
    }

    echo $output;
}

function generate_page_url($page, $subpage = false, $suffix = false, $return = false)
{
    $append = ($subpage !== false) ? '&amp;subpage=' . $subpage : '';
    $append .= ($suffix !== false) ? $suffix : '';

    $url = generate_url('adm/index.php?page=' . $page . $append);

    if ($return)
    {
        return $url;
    }

    echo $url;
}

function load_hook($page = '', $subpage = '')
{
    global $admin_pages, $auth;

    if ($page == '')
    {
        load_hook('dashboard');
    }

    // Only page
    if ($page != '' && $subpage == '')
    {
        if (isset($admin_pages[$page]))
        {
            if (!$auth->has_perm($admin_pages[$page]['capability']))
            {
                trigger_error('PERMISSION_DENIED');
            }
            call_user_func($admin_pages[$page]['function']);
        }
        else
        {
            /**
            * @todo Page not found error
            */
            trigger_error('page not found');
        }
    }

    if ($page != '' && $subpage != '')
    {
        if (isset($admin_pages[$page]['subpages'][$subpage]))
        {
            if (!$auth->has_perm($admin_pages[$page]['subpages'][$subpage]['capability']))
            {
                trigger_error('PERMISSION_DENIED');
            }
            call_user_func($admin_pages[$page]['subpages'][$subpage]['function']);
        }
        else
        {
            /**
            * @todo Page not found error
            */
            trigger_error('page not found');
        }
    }
}



?>

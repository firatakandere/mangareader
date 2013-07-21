<?php

function admin_menu_list($return = false)
{
    global $acl, $admin_pages;

    $output = '<ul class="nav nav-list">';

    foreach ($admin_pages as $slug_name => $atts)
    {
        if ($acl->has_perm($atts['capability']))
        {
            $page_url = generate_page_url($slug_name, false, false, true);
            $output .= '<li class="nav-header"><a href="' . $page_url . '">' . $atts['menu_title'] . '</a></li>';
            if (isset($atts['subpages']) && sizeof($atts['subpages']))
            {
                foreach ($atts['subpages'] as $sub_slug_name => $sub_atts)
                {
                    if ($acl->has_perm($sub_atts['capability']))
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
    global $admin_pages, $acl;


    if ($page == '')
    {
        load_hook('dashboard');
    }

    // Only page
    if ($page != '' && $subpage == '')
    {
        if (isset($admin_pages[$page]))
        {
            if (!$acl->has_perm($admin_pages[$page]['capability']))
            {
                trigger_error('PERMISSION_DENIED');
                return false;
            }
            call_user_func($admin_pages[$page]['function']);
            return true;
        }
        else
        {
            /**
            * @todo Page not found error
            */
            trigger_error('page not found');
            return false;
        }
    }

    if ($page != '' && $subpage != '')
    {
        if (isset($admin_pages[$page]['subpages'][$subpage]))
        {
            if (!$acl->has_perm($admin_pages[$page]['subpages'][$subpage]['capability']))
            {
                /**
                * @todo Permission denied error
                */
                trigger_error('permission denied');
                return false;
            }
            call_user_func($admin_pages[$page]['subpages'][$subpage]['function']);
            return true;
        }
        else
        {
            /**
            * @todo Page not found error
            */
            trigger_error('page not found');
            return false;
        }
    }
}

?>

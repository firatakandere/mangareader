<?php

/**
* @ignore
*/
if (!defined('IN_MANGAREADER'))
{
    exit;
}

function get_footer($name = null)
{
    if (isset($name))
    {
        locate_template("footer-{$name}.php", true);
    }
    else
    {
        locate_template('footer.php', true);
    }
    page_footer();
}

function get_header($name = null)
{
    page_header();
    if (isset($name))
    {
        locate_template("header-{$name}.php", true);
    }
    else
    {
        locate_template('header.php', true);
    }
}

function get_sidebar($name = null)
{
    if (isset($name))
    {
        locate_template("sidebar-{$name}.php", true);
    }
    else
    {
        locate_template('sidebar.php', true);
    }
}

function get_current_template_path()
{
    global $user, $mangareader_root_path;
    return $mangareader_root_path . 'templates/' . $user->data['template_path'];
}

function locate_template($template_names, $load = false, $require_once = true)
{
    $current_template_path = get_current_template_path();

    $located = '';
    foreach ((array) $template_names as $template_name)
    {
        if (!$template_name)
        {
            continue;
        }
        if (file_exists($current_template_path . '/' . $template_name))
        {
            $located = $current_template_path . '/' . $template_name;
            break;
        }
    }

    if ($load && $located != '')
    {
        load_template($located, $require_once);
    }

    return $located;
}

function load_template($_template_file, $require_once = true)
{
    /**
    * @todo Lots of global variables come here
    */
    global $cache, $config, $data, $user;

    if ($require_once)
    {
        require_once($_template_file);
    }
    else
    {
        require($_template_file);
    }
}

function get_charset($return = false)
{
    /**
    * @todo here
    */
    if ($return)
    {
        return 'UTF-8';
    }
    echo 'UTF-8';
}

function get_template_directory_uri($return = false)
{
    global $user;
    $uri = generate_url('templates/' . $user->data['template_path']);
    if ($return)
    {
        return $uri;
    }
    echo $uri;
}

function get_home_uri($return = false)
{
    $url = generate_url('', '');

    if ($return)
    {
        return $url;
    }
    echo $url;
}

function get_login_uri($return = false)
{
    $url = generate_url('ucp.php?mode=login', 'user/login/');

    if ($return)
    {
        return $url;
    }

    echo $url;
}

function get_register_uri($return = false)
{
    $url = generate_url('ucp.php?mode=register', 'user/register/');

    if ($return)
    {
        return $url;
    }

    echo $url;
}

function language_attributes()
{
    echo '';
    /**
    * @todo here
    */
}

function mr_title()
{
    /**
    * @todo here
    */
    echo '';
}

function mr_head()
{
    do_action('mr_head');
}

function mr_footer()
{
    do_action('mr_footer');
}
?>

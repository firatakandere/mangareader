<?php

// Default Dashboard
add_action('admin_menu_pages', 'add_default_dashboard');
function add_default_dashboard()
{
    add_menu_page('Dashboard', 'Dashboard', 'capability', 'dashboard', 'default_dashboard', 2);
}


function default_dashboard()
{

}

?>

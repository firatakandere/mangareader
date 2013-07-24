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
* A planned hooking system
* @package hooks
*/
class Hooks
{
    private $hooks = array();

    public function add_action($where, $callback, $priority = 50)
    {
        if (!isset($this->hooks[$where]) || !is_array($this->hooks[$where]))
        {
            $this->hooks[$where] = array();
        }
        $this->hooks[$where][$callback] = $priority;
    }

    public function remove_action($where, $callback)
    {
        if (isset($this->hooks[$where][$callback]))
        {
            unset($this->hooks[$where][$callback]);
        }
    }

    public function execute($where, $args = array())
    {
        if ((isset($this->hooks[$where])) && is_array($this->hooks[$where]))
        {
            arsort($this->hooks[$where]);

            foreach ($this->hooks[$where] as $callback => $priority)
            {
                call_user_func_array($callback, $args);
            }
        }
    }
}

$hooking_daemon = new Hooks();
$admin_pages = array();
$registered_scripts = array();

/**
* Add new action to the hook
*
* @param string $where The string which specify the hooking grup
* @param string $callback The function name which will be called
* @param int $priority Callback priority
*
* @return void
*/
function add_action($where, $callback, $priority = 50)
{
    global $hooking_daemon;
    $hooking_daemon->add_action($where, $callback, $priority);
}

/**
* Remove an action from the hook
*
* @param string $where The string which specify the hooking grup
* @param string $callback The function name which will be removed from hooking group
*
* @return void
*/
function remove_action($where, $callback)
{
    global $hooking_daemon;
    $hooking_daemon->remove_action($where, $callback);
}

/**
* Execute an hooking group
*
* @param string $where The string which specify the hooking group that will be executed
* @param array $args Function arguments
*
* @return void
*/
function do_action($where, $args = array())
{
    global $hooking_daemon;
    $hooking_daemon->execute($where, $args = array());
}

?>

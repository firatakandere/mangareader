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
if (!defined('IN_MANGAREADER'))
{
    exit;
}

if (!file_exists($mangareader_root_path . 'config.php') || filesize($mangareader_root_path . 'config.php') === 0)
{
    if (!headers_sent())
    {
        header('Location: ./install/');
    }
    else
    {
        die('Configuration file does not exist. Please go installation path to install mangareader');
    }
}

require($mangareader_root_path . 'includes/startup.php');
require($mangareader_root_path . 'includes/functions.php');
require($mangareader_root_path . 'config.php');
require($mangareader_root_path . 'includes/constants.php');
require($mangareader_root_path . 'includes/class-database.php');
require($mangareader_root_path . 'includes/class-cache.php');
require($mangareader_root_path . 'includes/class-user.php');
require($mangareader_root_path . 'includes/class-hooks.php');
require($mangareader_root_path . 'includes/plugin.php');
require($mangareader_root_path . 'includes/functions-template.php');
require($mangareader_root_path . 'includes/utf/utf_tools.php');
require($mangareader_root_path . 'includes/class-auth.php');

set_error_handler(defined('MANGAREADER_MSG_HANDLER') ? MANGAREADER_MSG_HANDLER : 'msg_handler');

// If the database port is not empty, suffix it to the database host with ':' separator
if (!empty($dbport))
{
    $dbhost .= ':' . $dbport;
}

$dsn .= ':dbname=' . $dbname . ';host=' . $dbhost;

// Database connection
try
{
    $db = new Database($dsn, $dbuser, $dbpass);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $exception)
{
    trigger_error('Database connection failed: ' . $exception->getMessage(), E_USER_ERROR);
}

// We do not need this anymore, better to get rid of it for security
unset($dbpass);

$cache = new Cache();

// Try to fetch config from cache, otherwise fetch it via sql and store it into cache
if (($config = $cache->get('config')) === false)
{
    $sql = 'SELECT * FROM ' . CONFIG_TABLE;
    $sth = $db->prepare($sql);
    $sth->execute();
    while ($row = $sth->fetch(PDO::FETCH_ASSOC))
    {
        $config[$row['config_name']] = $row['config_value'];
    }
    $cache->put('config', $config);
}

// Let's kill expired sessions first
$expiration = time() - ((int) $config['session_span'] * 60);
$sql = 'DELETE FROM ' . SESSIONS_TABLE . '
        WHERE session_last_visit < ' . (int)$expiration;
$db->query($sql);

$user = new User();

$lang_domains = array();

$auth = new Auth();

if (file_exists($mangareader_root_path . 'install') && !defined('IN_INSTALL'))
{
    //trigger_error('REMOVE_INSTALL_PATH', E_USER_WARNING);
}

// If currently on admininstration panel, load default hooks
if (is_admin_panel())
{
    foreach (glob($mangareader_admin_root_path . 'hooks/*.php') as $filename)
    {
        include_once($filename);
    }
}
do_action('mr_init');

// Initialize plugins here

?>
